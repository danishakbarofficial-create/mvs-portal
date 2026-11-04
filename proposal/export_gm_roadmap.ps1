$ppt = New-Object -ComObject PowerPoint.Application
try {
    $presPath = "C:\laragon\www\MVS Project Pulse\proposal\MVS_Project_Pulse_GM_Roadmap.pptx"
    $pres = $ppt.Presentations.Open($presPath, 2, 0, 0)
    
    $outPdf = "C:\laragon\www\MVS Project Pulse\proposal\MVS_Project_Pulse_GM_Roadmap.pdf"
    $pres.SaveAs($outPdf, 32)
    
    $outFolder = "C:\laragon\www\MVS Project Pulse\proposal\gm_roadmap_previews"
    if (!(Test-Path $outFolder)) {
        New-Item -ItemType Directory -Path $outFolder | Out-Null
    }
    $pres.Export($outFolder, "PNG", 1920, 1080)
    Write-Output "Successfully exported GM Roadmap PDF and PNG previews!"
} catch {
    Write-Error $_
} finally {
    if ($pres) { $pres.Close() }
    if ($ppt) { $ppt.Quit() }
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
}
