$ppt = New-Object -ComObject PowerPoint.Application
try {
    $presPath = "C:\laragon\www\MVS Project Pulse\proposal\MVS_Project_Pulse_Executive_Proposal.pptx"
    $pres = $ppt.Presentations.Open($presPath, 2, 0, 0) # ReadOnly=2, Untitled=0, WithWindow=0
    
    $outPdf = "C:\laragon\www\MVS Project Pulse\proposal\MVS_Project_Pulse_Executive_Proposal_Slides.pdf"
    $pres.SaveAs($outPdf, 32) # 32 = ppSaveAsPDF
    
    $outFolder = "C:\laragon\www\MVS Project Pulse\proposal\slide_previews"
    if (!(Test-Path $outFolder)) {
        New-Item -ItemType Directory -Path $outFolder | Out-Null
    }
    $pres.Export($outFolder, "PNG", 1920, 1080)
    Write-Output "Successfully exported PDF and PNG slide images!"
} catch {
    Write-Error $_
} finally {
    if ($pres) { $pres.Close() }
    if ($ppt) { $ppt.Quit() }
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
}
