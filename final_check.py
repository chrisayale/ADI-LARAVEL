import json

print("=== FINAL VERIFICATION ===\n")

# Check both language files
for lang in ['fr', 'en']:
    with open(f'lang/{lang}.json', 'r', encoding='utf-8') as f:
        data = json.load(f)

    # Check for mojibake
    mojibake_patterns = ['Ã©', 'Ã¨', 'Ãª', 'Ã«', 'Ã ', 'Ã¢', 'Ã¤', 'Ã®', 'Ã¯', 'Ã´', 'Ã¶', 'Ã¹', 'Ã»', 'Ã¼', 'Ã§', 'Ã°', 'Ã±', 'Ã', 'Ã‰', 'Ã€', 'Ã¡', 'Ã£', 'Ã¥', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'Ã', 'ÃŸ']
    found = False
    for key, value in data.items():
        for pattern in mojibake_patterns:
            if pattern in value:
                print(f'{lang}.json MOJIBAKE in {key}: {value}')
                found = True

    if not found:
        print(f'✓ {lang}.json: Clean UTF-8, {len(data)} keys, no mojibake')

    # Verify file is valid UTF-8 without BOM
    with open(f'lang/{lang}.json', 'rb') as f:
        content = f.read()
    if content.startswith(b'\xef\xbb\xbf'):
        print(f'  WARNING: {lang}.json has BOM')
    else:
        print(f'  ✓ {lang}.json: No BOM')
    try:
        content.decode('utf-8')
        print(f'  ✓ {lang}.json: Valid UTF-8')
    except UnicodeDecodeError as e:
        print(f'  ✗ {lang}.json: Invalid UTF-8 at {e.start}')

print()
print("=== KEY TRANSLATIONS ===")
for lang_name, data in [('fr', fr), ('en', en)]:
    for key in ['savings', 'all_loans', 'applications', 'active', 'not_secure', 'last_login', 'first_login', 'secure_connection', 'membership_no', 'application_no', 'loan_no']:
        val = data.get(key, 'MISSING')
        print(f'  {lang_name}.{key} = {val}')

print()
print("=== STATUS TRANSLATIONS ===")
statuses = ['active', 'inactive', 'pending', 'approved', 'rejected', 'disbursed', 'completed', 'overdue', 'verified']
for lang_name, data in [('fr', fr), ('en', en)]:
    for status in statuses:
        val = data.get(status, 'MISSING')
        print(f'  {lang_name}.{status} = {val}')