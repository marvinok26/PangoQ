import os

EXCLUDED_DIRS = {"node_modules", "vendor", "tests", "storage"}
EXCLUDED_EXTENSIONS = {".jpg", ".jpeg", ".png", ".gif", ".webp", ".pdf", ".xml"}
EXCLUDED_FILES = {"package-lock.json", "composer.lock"}
OUTPUT_FILE = "bundled_code.txt"

def is_hidden(path):
    return any(part.startswith('.') for part in path.split(os.sep))

def should_skip_file(filepath, root):
    filename = os.path.basename(filepath)
    ext = os.path.splitext(filename)[1].lower()

    # Skip hidden files
    if is_hidden(os.path.relpath(filepath)):
        return True

    # Skip excluded files and extensions
    if ext in EXCLUDED_EXTENSIONS or filename in EXCLUDED_FILES:
        return True

    # Skip files in excluded dirs
    for part in os.path.relpath(root).split(os.sep):
        if part in EXCLUDED_DIRS:
            return True

    # Skip empty files
    if os.path.getsize(filepath) == 0:
        return True

    return False

with open(OUTPUT_FILE, "w", encoding="utf-8") as output:
    for root, dirs, files in os.walk("."):
        # Skip hidden directories
        dirs[:] = [d for d in dirs if not d.startswith(".") and d not in EXCLUDED_DIRS]

        for file in files:
            filepath = os.path.join(root, file)
            if should_skip_file(filepath, root):
                continue
            try:
                with open(filepath, "r", encoding="utf-8") as f:
                    output.write(f"\n\n=== File: {os.path.relpath(filepath)} ===\n\n")
                    output.write(f.read())
            except Exception as e:
                print(f"Skipped {filepath} due to error: {e}")
