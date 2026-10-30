import json

# Final verification of both files
for lang in ['fr', 'en']:
    with open(f'lang/{lang}.json', 'r', encoding='utf-8') as f:
        data = json.load(f)

    # Check for mojibake
    mojibake_patterns = ['Ã©', 'Ã¨', 'Ãª', 'Ã«', 'Ã ', 'Ã¢', 'Ã¤', 'Ã®', 'Ã¯', 'Ã´', 'Ã¶', 'Ã¹', 'Ã»', 'Ã¼', 'Ã§', 'Ã°', 'Ã±', 'Ã', 'Ã‰', 'Ã€', 'Ã¡', 'Ã£', 'Ã¥']
    found = False
    for key, value in data.items():
        for pattern in mojibake_patterns:
            if pattern in value:
                print(f'{lang}.json MOJIBAKE in {key}: {value}')
                found = True

    if not found:
        print(f'{lang}.json: Clean UTF-8, {len(data)} keys, no mojibake')

    # Verify file is valid UTF-8 without BOM
    with open(f'lang/{lang}.json', 'rb') as f:
        content = f.read()
    if content.startswith(b'\xef\xbb\xbf'):
        print(f'  WARNING: {lang}.json has BOM')
    else:
        print(f'  {lang}.json: No BOM')
    try:
        content.decode('utf-8')
        print(f'  {lang}.json: Valid UTF-8')
    except UnicodeDecodeError as e:
        print(f'  {lang}.json: Invalid UTF-8 at {e.start}')

print()
print('Database config SQLite:')
with open('config/database.php', 'r', encoding='utf-8') as f:
    content = f.read()
if "'charset' => 'utf8mb4'" in content and 'sqlite' in content:
    print('SQLite configured with utf8mb4 charset')
if 'PDO::SQLITE_ATTR_OPEN_FLAGS' in content:
    print('SQLite configured with proper PDO options')