$status = git status --porcelain
if (-not $status) {
    Write-Host "No changes to push."
    exit
}

# Extract filenames. Porcelain format is "XY PATH"
$files = $status | ForEach-Object { 
    $line = $_.ToString()
    if ($line.Length -gt 3) {
        $path = $line.Substring(3).Trim()
        # Remove quotes if git added them
        if ($path.StartsWith('"') -and $path.EndsWith('"')) { 
            $path = $path.Substring(1, $path.Length-2) 
        }
        $path
    }
}

$batch = @()

foreach ($file in $files) {
    if ([string]::IsNullOrWhiteSpace($file)) { continue }
    
    $batch += $file

    if ($batch.Count -eq 2) {
        $f1 = $batch[0]
        $f2 = $batch[1]
        Write-Host "Adding and pushing: $f1, $f2"
        
        git add "$f1" "$f2"
        git commit -m "Update $f1 and $f2"
        git push
        
        if ($LASTEXITCODE -ne 0) {
            Write-Host "Error pushing batch. stopping."
            exit
        }
        
        $batch = @()
    }
}

# Handle remaining file
if ($batch.Count -gt 0) {
    $f1 = $batch[0]
    Write-Host "Adding and pushing remaining file: $f1"
    git add "$f1"
    git commit -m "Update $f1"
    git push
}

Write-Host "Done."
