<?php
/**
 * MVS ICT & Telecom - Live Master Excel Export Engine
 * Exports the live system records into the exact original v4 Excel layout
 * preserving all sheets, dashboards, styling, borders, and formulas.
 */

require_once __DIR__ . '/../auth.php';

// Authorized roles
$currentUser = Auth::requireLogin(['admin', 'executive']);

$dbPath = __DIR__ . '/../data/mvs_sales.sqlite';
$templatePath = __DIR__ . '/../Project_Tracking_Report Asayil v4.xlsx';

if (!file_exists($templatePath)) {
    http_response_code(500);
    die("Master Excel template not found at: " . htmlspecialchars($templatePath));
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch live pipeline records
    $stmt = $pdo->query("SELECT * FROM v4_pipeline ORDER BY COALESCE(row_no, id) ASC, id ASC");
    $pipelineRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch live project records
    $stmt = $pdo->query("SELECT * FROM v4_projects ORDER BY COALESCE(row_no, id) ASC, id ASC");
    $projectsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    http_response_code(500);
    die("Database query error: " . htmlspecialchars($e->getMessage()));
}

// Convert date string (YYYY-MM-DD or standard formats) to Excel serial number (days since 1899-12-30)
function toExcelSerialDate($str) {
    if (!$str || !trim($str)) return null;
    $ts = strtotime(trim($str));
    if (!$ts) return null;
    $d0 = new DateTime('1899-12-30');
    $d1 = new DateTime(date('Y-m-d', $ts));
    return $d0->diff($d1)->days;
}

// Helper to write OpenXML cell contents safely preserving formatting & existing formulas
function setCellContent(DOMDocument $doc, string $ns, DOMElement $c, $val, string $type = 'text') {
    // Preserve formula <f> if present
    $hasFormula = false;
    foreach ($c->childNodes as $child) {
        if ($child->localName === 'f') {
            $hasFormula = true;
            break;
        }
    }

    if ($hasFormula) {
        // Only strip cached <v> so Excel calculates freshly
        $toRemove = [];
        foreach ($c->childNodes as $child) {
            if ($child->localName === 'v' || $child->localName === 'is') {
                $toRemove[] = $child;
            }
        }
        foreach ($toRemove as $node) {
            $c->removeChild($node);
        }
        return;
    }

    // For non-formula cells, clear old contents
    while ($c->hasChildNodes()) {
        $c->removeChild($c->firstChild);
    }

    if ($val === null || $val === '') {
        $c->removeAttribute('t');
        return;
    }

    if ($type === 'text') {
        $c->setAttribute('t', 'inlineStr');
        $is = $doc->createElementNS($ns, 'is');
        $t = $doc->createElementNS($ns, 't');
        $t->textContent = (string)$val;
        $is->appendChild($t);
        $c->appendChild($is);
    } elseif ($type === 'number') {
        $c->removeAttribute('t');
        $v = $doc->createElementNS($ns, 'v');
        $v->textContent = (string)$val;
        $c->appendChild($v);
    } elseif ($type === 'date') {
        $serial = toExcelSerialDate($val);
        if ($serial !== null) {
            $c->removeAttribute('t');
            $v = $doc->createElementNS($ns, 'v');
            $v->textContent = (string)$serial;
            $c->appendChild($v);
        } else {
            // Fallback as clean string
            $c->setAttribute('t', 'inlineStr');
            $is = $doc->createElementNS($ns, 'is');
            $t = $doc->createElementNS($ns, 't');
            $t->textContent = (string)$val;
            $is->appendChild($t);
            $c->appendChild($is);
        }
    }
}

// Create isolated temporary file
$tempExportFile = tempnam(sys_get_temp_dir(), 'mvs_excel_') . '.xlsx';
if (!copy($templatePath, $tempExportFile)) {
    http_response_code(500);
    die("Failed to create temporary export file.");
}

$zip = new ZipArchive();
if ($zip->open($tempExportFile) !== TRUE) {
    @unlink($tempExportFile);
    http_response_code(500);
    die("Failed to initialize Excel archive engine.");
}

$NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

// -------------------------------------------------------------
// 1. Process Pipeline (sheet3.xml)
// -------------------------------------------------------------
$sheet3Xml = $zip->getFromName('xl/worksheets/sheet3.xml');
if ($sheet3Xml !== false) {
    $doc3 = new DOMDocument();
    $doc3->loadXML($sheet3Xml);
    $xpath3 = new DOMXPath($doc3);
    $xpath3->registerNamespace('x', $NS);

    $pipelineColMap = [
        'B' => ['field' => 'sales_person', 'type' => 'text'],
        'C' => ['field' => 'project_name', 'type' => 'text'],
        'D' => ['field' => 'client', 'type' => 'text'],
        'E' => ['field' => 'main_contractor', 'type' => 'text'],
        'F' => ['field' => 'consultant', 'type' => 'text'],
        'G' => ['field' => 'scope_of_work', 'type' => 'text'],
        'H' => ['field' => 'date_received', 'type' => 'date'],
        'I' => ['field' => 'submission_date', 'type' => 'date'],
        'J' => ['field' => 'quotation_no', 'type' => 'text'],
        'K' => ['field' => 'quotation_status', 'type' => 'text'],
        'L' => ['field' => 'total_sar', 'type' => 'number'],
        'M' => ['field' => 'expected_closing', 'type' => 'date'],
        'N' => ['field' => 'quotation_valid_until', 'type' => 'date'],
        'O' => ['field' => 'contact_person', 'type' => 'text'],
        'P' => ['field' => 'contact_details', 'type' => 'text'],
        'Q' => ['field' => 'decision_status', 'type' => 'text'],
        'R' => ['field' => 'missing_info', 'type' => 'text'],
        'S' => ['field' => 'next_step', 'type' => 'text'],
        'T' => ['field' => 'follow_up_date', 'type' => 'date'],
        'U' => ['field' => 'notes', 'type' => 'text'],
        'V' => ['field' => 'additional_notes', 'type' => 'text']
    ];

    for ($r = 5; $r <= 104; $r++) {
        $idx = $r - 5;
        $record = isset($pipelineRows[$idx]) ? $pipelineRows[$idx] : null;

        // Column A: Row sequence number
        $nodesA = $xpath3->query("//x:row[@r='$r']/x:c[@r='A$r']");
        if ($nodesA->length > 0) {
            $cA = $nodesA->item(0);
            if ($record) {
                setCellContent($doc3, $NS, $cA, $idx + 1, 'number');
            } else {
                setCellContent($doc3, $NS, $cA, '', 'text');
            }
        }

        // Columns B through V
        foreach ($pipelineColMap as $col => $info) {
            $cellRef = $col . $r;
            $nodes = $xpath3->query("//x:row[@r='$r']/x:c[@r='$cellRef']");
            if ($nodes->length > 0) {
                $c = $nodes->item(0);
                $val = $record ? ($record[$info['field']] ?? '') : '';
                setCellContent($doc3, $NS, $c, $val, $info['type']);
            }
        }
    }
    $zip->addFromString('xl/worksheets/sheet3.xml', $doc3->saveXML());
}

// -------------------------------------------------------------
// 2. Process Awarded Projects & Milestones (sheet4.xml)
// -------------------------------------------------------------
$sheet4Xml = $zip->getFromName('xl/worksheets/sheet4.xml');
if ($sheet4Xml !== false) {
    $doc4 = new DOMDocument();
    $doc4->loadXML($sheet4Xml);
    $xpath4 = new DOMXPath($doc4);
    $xpath4->registerNamespace('x', $NS);

    $projectColMap = [
        'B' => ['field' => 'project_manager', 'type' => 'text'],
        'C' => ['field' => 'project_name', 'type' => 'text'],
        'D' => ['field' => 'customer', 'type' => 'text'],
        'E' => ['field' => 'po_status', 'type' => 'text'],
        'F' => ['field' => 'po_no', 'type' => 'text'],
        'G' => ['field' => 'po_date', 'type' => 'date'],
        'H' => ['field' => 'total_po_value', 'type' => 'number'],
        'I' => ['field' => 'expected_closing', 'type' => 'date'],
        // J is formula Months to close
        'K' => ['field' => 'milestone_no', 'type' => 'text'],
        'L' => ['field' => 'milestone_desc', 'type' => 'text'],
        'M' => ['field' => 'milestone_value', 'type' => 'number'],
        'N' => ['field' => 'milestone_status', 'type' => 'text'],
        'O' => ['field' => 'technical_report', 'type' => 'text'],
        // P is formula Invoice Status
        'Q' => ['field' => 'invoice_no', 'type' => 'text'],
        'R' => ['field' => 'invoice_date', 'type' => 'date'],
        'S' => ['field' => 'invoice_amount', 'type' => 'number'],
        'T' => ['field' => 'collection_date', 'type' => 'date'],
        'U' => ['field' => 'collected_amount', 'type' => 'number'],
        'V' => ['field' => 'collection_status', 'type' => 'text'],
        // W is formula Pending Collection
        // X is formula To Be Invoiced
        'Y' => ['field' => 'notes', 'type' => 'text'],
        'Z' => ['field' => 'additional_notes', 'type' => 'text']
    ];

    for ($r = 5; $r <= 104; $r++) {
        $idx = $r - 5;
        $record = isset($projectsRows[$idx]) ? $projectsRows[$idx] : null;

        // Column A: Row sequence number
        $nodesA = $xpath4->query("//x:row[@r='$r']/x:c[@r='A$r']");
        if ($nodesA->length > 0) {
            $cA = $nodesA->item(0);
            if ($record) {
                setCellContent($doc4, $NS, $cA, $idx + 1, 'number');
            } else {
                setCellContent($doc4, $NS, $cA, '', 'text');
            }
        }

        // Columns B through Z
        foreach ($projectColMap as $col => $info) {
            $cellRef = $col . $r;
            $nodes = $xpath4->query("//x:row[@r='$r']/x:c[@r='$cellRef']");
            if ($nodes->length > 0) {
                $c = $nodes->item(0);
                $val = $record ? ($record[$info['field']] ?? '') : '';
                setCellContent($doc4, $NS, $c, $val, $info['type']);
            }
        }
    }
    $zip->addFromString('xl/worksheets/sheet4.xml', $doc4->saveXML());
}

// -------------------------------------------------------------
// 3. Update workbook calculation properties to force full recalc on open
// -------------------------------------------------------------
$wbXml = $zip->getFromName('xl/workbook.xml');
if ($wbXml !== false) {
    $docWb = new DOMDocument();
    $docWb->loadXML($wbXml);
    $calcPrList = $docWb->getElementsByTagName('calcPr');
    if ($calcPrList->length > 0) {
        $calcPr = $calcPrList->item(0);
        $calcPr->setAttribute('fullCalcOnLoad', '1');
        $calcPr->setAttribute('forceFullCalc', '1');
    }
    $zip->addFromString('xl/workbook.xml', $docWb->saveXML());
}

$zip->close();

// -------------------------------------------------------------
// 4. Stream response with professional Excel headers
// -------------------------------------------------------------
$downloadFilename = "MVS Master Project Tracking.xlsx";

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $downloadFilename . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($tempExportFile));

// Flush output buffers to prevent corruption
while (ob_get_level()) {
    ob_end_clean();
}

readfile($tempExportFile);
@unlink($tempExportFile);
exit;
