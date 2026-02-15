import re
import json
import os

def generate_manifest():
    if not os.path.exists('vite.config.js'):
        print("vite.config.js not found.")
        return

    with open('vite.config.js', 'r') as f:
        content = f.read()

    # Regex to find the input array
    match = re.search(r"input:\s*\[(.*?)\]", content, re.DOTALL)
    if not match:
        print("Could not find input array in vite.config.js")
        return

    input_content = match.group(1)

    # Extract file paths (simple regex for strings)
    files = re.findall(r"['\"](.*?)['\"]", input_content)

    manifest = {}
    for file in files:
        # Simple mapping
        manifest[file] = {
            "file": "assets/dummy.js",
            "src": file,
            "isEntry": True
        }

    # Manually ensure resources/css/app.css and resources/js/app.js are in manifest
    # This is often needed if they are imported indirectly or vite config is complex
    manifest['resources/css/app.css'] = {"file": "assets/app.css", "src": "resources/css/app.css", "isEntry": True}
    manifest['resources/js/app.js'] = {"file": "assets/app.js", "src": "resources/js/app.js", "isEntry": True}

    os.makedirs('public/build', exist_ok=True)
    with open('public/build/manifest.json', 'w') as f:
        json.dump(manifest, f, indent=4)

    print(f"Generated manifest.json with {len(files) + 2} entries.")

if __name__ == "__main__":
    generate_manifest()
