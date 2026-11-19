# Stop any lingering PowerPoint processes to avoid file lock
Get-Process -Name POWERPNT -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Milliseconds 500

$ppt = New-Object -ComObject PowerPoint.Application
try {
    $presPath = "C:\laragon\www\MVS Project Pulse\Presentation\MVS Weekly Tasks and IT Assets Presentation Week 47.pptx"
    $pres = $ppt.Presentations.Open($presPath, 2, 0, 0)
    
    $outPdf1 = "C:\laragon\www\MVS Project Pulse\Presentation\MVS Weekly Tasks and IT Assets Presentation Week 47.pdf"
    $pres.SaveAs($outPdf1, 32) # 32 = ppSaveAsPDF
    Write-Output "Successfully exported: $outPdf1"

    $outPdf2 = "C:\laragon\www\MVS Project Pulse\Presentation\MVS Weekly Tasks and IT Assets Presentation.pdf"
    $pres.SaveAs($outPdf2, 32)
    Write-Output "Successfully updated: $outPdf2"

    $outPdf3 = "C:\Users\danis\Downloads\Week 47 presentations\MVS Weekly Tasks and IT Assets Presentation Week 47.pdf"
    $pres.SaveAs($outPdf3, 32)
    Write-Output "Successfully exported: $outPdf3"
} catch {
    Write-Error $_
} finally {
    if ($pres) { $pres.Close() }
    if ($ppt) { $ppt.Quit() }
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
}
