import json, sys

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
                with open('check_result.txt', 'a', encoding='utf-8') as out:
                    out.write(f'{lang}.json MOJIBAKE in {key}: {value}\n')
                found = True

    if not found:
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'OK: {lang}.json: Clean UTF-8, {len(data)} keys, no mojibake\n')

    # Verify file is valid UTF-8 without BOM
    with open(f'lang/{lang}.json', 'rb') as f:
        content = f.read()
    if content.startswith(b'\xef\xbb\xbf'):
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'WARNING: {lang}.json has BOM\n')
    else:
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'OK: {lang}.json: No BOM\n')
    try:
        content.decode('utf-8')
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'OK: {lang}.json: Valid UTF-8\n')
    except UnicodeDecodeError as e:
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'ERROR: {lang}.json: Invalid UTF-8 at {e.start}\n')

with open('check_result.txt', 'a', encoding='utf-8') as out:
    out.write('\n=== KEY TRANSLATIONS ===\n')

fr = json.load(open('lang/fr.json', 'r', encoding='utf-8'))
en = json.load(open('lang/en.json', 'r', encoding='utf-8'))

for lang_name, data in [('fr', fr), ('en', en)]:
    for key in ['savings', 'all_loans', 'applications', 'active', 'not_secure', 'last_login', 'first_login', 'secure_connection', 'membership_no', 'application_no', 'loan_no']:
        val = data.get(key, 'MISSING')
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'  {lang_name}.{key} = {val}\n')

with open('check_result.txt', 'a', encoding='utf-8') as out:
    out.write('\n=== STATUS TRANSLATIONS ===\n')
statuses = ['active', 'inactive', 'pending', 'approved', 'rejected', 'disbursed', 'completed', 'overdue', 'verified']
for lang_name, data in [('fr', fr), ('en', en)]:
    for status in statuses:
        val = data.get(status, 'MISSING')
        with open('check_result.txt', 'a', encoding='utf-8') as out:
            out.write(f'  {lang_name}.{status} = {val}\n')

print("Done - check check_result.txt")