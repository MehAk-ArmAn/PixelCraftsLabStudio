#!/usr/bin/env python3
from pathlib import Path
import hashlib
import sys

root = Path(__file__).resolve().parents[1]
design = root / "resources" / "pixelcraftslab" / "PixelCraftsLab Site.dc.html"
required = [
    design,
    root / "public" / "support.js",
    root / "routes" / "web.php",
    root / "app" / "Http" / "Controllers" / "PixelCraftsLabSiteController.php",
]

ok = True
for p in required:
    if p.exists():
        print("OK:", p)
    else:
        print("MISSING:", p)
        ok = False

if design.exists():
    print("Locked design SHA-256:", hashlib.sha256(design.read_bytes()).hexdigest())

assets = root / "public" / "assets"
print(("OK:" if assets.exists() else "WARNING:"), assets)

sys.exit(0 if ok else 1)
