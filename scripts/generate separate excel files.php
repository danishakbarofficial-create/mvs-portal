<?php
/**
 * Script to generate separate Excel workbooks:
 * 1. MVS PipeLines Tracking.xlsx
 * 2. MVS Projects Tracking.xlsx
 */

$dbPath = 'C:/laragon/www/MVS Project Pulse/data/mvs_sales.sqlite';
$templatePath = 'C:/laragon/www/MVS Project Pulse/Project_Tracking_Report Asayil v4.xlsx';

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pipelineRows = $pdo->query("SELECT * FROM v4_pipeline ORDER BY COALESCE(row_no, id) ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
$projectsRows = $pdo->query("SELECT * FROM v4_projects ORDER BY COALESCE(row_no, id) ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);

function toExcelSerialDate($str) {
    if (!$str || !trim($str)) return null;
    $ts = strtotime(trim($str));
    if (!$ts) return null;
    $d0 = new DateTime('1899-12-30');
    $d1 = new DateTime(date('Y-m-d', $ts));
    return $d0->diff($d1)->days;
}

function setCellContent(DOMDocument $doc, string $ns, DOMElement $c, $val, string $type = 'text') {
    $hasFormula = false;
    foreach ($c->childNodes as $child) {
        if ($child->localName === 'f') {
            $hasFormula = true;
            break;
        }
    }
    if ($hasFormula) {
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
            $c->setAttribute('t', 'inlineStr');
            $is = $doc->createElementNS($ns, 'is');
            $t = $doc->createElementNS($ns, 't');
            $t->textContent = (string)$val;
            $is->appendChild($t);
            $c->appendChild($is);
        }
    }
}

function generateWorkbook($targetType, $destinationPath, $templatePath, $pipelineRows, $projectsRows) {
    copy($templatePath, $destinationPath);
    $zip = new ZipArchive();
    if ($zip->open($destinationPath) !== TRUE) {
        throw new Exception("Cannot open zip archive at $destinationPath");
    }

    $NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    // 1. Process Pipeline if needed
    if ($targetType === 'pipelines' || $targetType === 'all') {
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

                $nodesA = $xpath3->query("//x:row[@r='$r']/x:c[@r='A$r']");
                if ($nodesA->length > 0) {
                    $cA = $nodesA->item(0);
                    setCellContent($doc3, $NS, $cA, $record ? ($idx + 1) : '', $record ? 'number' : 'text');
                }

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
    }

    // 2. Process Projects if needed
    if ($targetType === 'projects' || $targetType === 'all') {
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
                'K' => ['field' => 'milestone_no', 'type' => 'text'],
                'L' => ['field' => 'milestone_desc', 'type' => 'text'],
                'M' => ['field' => 'milestone_value', 'type' => 'number'],
                'N' => ['field' => 'milestone_status', 'type' => 'text'],
                'O' => ['field' => 'technical_report', 'type' => 'text'],
                'Q' => ['field' => 'invoice_no', 'type' => 'text'],
                'R' => ['field' => 'invoice_date', 'type' => 'date'],
                'S' => ['field' => 'invoice_amount', 'type' => 'number'],
                'T' => ['field' => 'collection_date', 'type' => 'date'],
                'U' => ['field' => 'collected_amount', 'type' => 'number'],
                'V' => ['field' => 'collection_status', 'type' => 'text'],
                'Y' => ['field' => 'notes', 'type' => 'text'],
                'Z' => ['field' => 'additional_notes', 'type' => 'text']
            ];

            for ($r = 5; $r <= 104; $r++) {
                $idx = $r - 5;
                $record = isset($projectsRows[$idx]) ? $projectsRows[$idx] : null;

                $nodesA = $xpath4->query("//x:row[@r='$r']/x:c[@r='A$r']");
                if ($nodesA->length > 0) {
                    $cA = $nodesA->item(0);
                    setCellContent($doc4, $NS, $cA, $record ? ($idx + 1) : '', $record ? 'number' : 'text');
                }

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
    }

    // 3. Update workbook.xml sheets & recalc settings
    $wbXml = $zip->getFromName('xl/workbook.xml');
    if ($wbXml !== false) {
        $docWb = new DOMDocument();
        $docWb->loadXML($wbXml);
        $xpathWb = new DOMXPath($docWb);
        $xpathWb->registerNamespace('x', $NS);

        $calcPrList = $docWb->getElementsByTagName('calcPr');
        if ($calcPrList->length > 0) {
            $calcPr = $calcPrList->item(0);
            $calcPr->setAttribute('fullCalcOnLoad', '1');
            $calcPr->setAttribute('forceFullCalc', '1');
        }

        // Filter sheets based on type
        $sheetsParent = $docWb->getElementsByTagName('sheets')->item(0);
        if ($sheetsParent) {
            $sheetsToRemove = [];
            if ($targetType === 'pipelines') {
                $sheetsToRemove = ['Projects Dashboard', 'Projects'];
            } elseif ($targetType === 'projects') {
                $sheetsToRemove = ['Sales Dashboard', 'Pipeline'];
            }

            foreach (iterator_to_array($sheetsParent->childNodes) as $node) {
                if ($node instanceof DOMElement && in_array($node->getAttribute('name'), $sheetsToRemove)) {
                    $sheetsParent->removeChild($node);
                }
            }
        }

        $zip->addFromString('xl/workbook.xml', $docWb->saveXML());
    }

    $zip->close();
}

$pipeFile = 'C:/laragon/www/MVS Project Pulse/MVS PipeLines Tracking.xlsx';
$projFile = 'C:/laragon/www/MVS Project Pulse/MVS Projects Tracking.xlsx';

generateWorkbook('pipelines', $pipeFile, $templatePath, $pipelineRows, $projectsRows);
generateWorkbook('projects', $projFile, $templatePath, $pipelineRows, $projectsRows);

// Also copy to Downloads folder for immediate access
$downloadsDir = 'C:/Users/danis/Downloads';
if (is_dir($downloadsDir)) {
    copy($pipeFile, $downloadsDir . '/MVS PipeLines Tracking.xlsx');
    copy($projFile, $downloadsDir . '/MVS Projects Tracking.xlsx');
}

echo "SUCCESS: Generated both files successfully!\n";
