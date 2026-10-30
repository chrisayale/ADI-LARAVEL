import json

with open('lang/fr.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

print(f'fr.json: Valid JSON with {len(data)} keys')

# Check for mojibake patterns in the decoded strings
mojibake_patterns = [
    'Ã©', 'Ã¨', 'Ãª', 'Ã«', 'Ã ', 'Ã¢', 'Ã¤', 'Ã®', 'Ã¯',
    'Ã´', 'Ã¶', 'Ã¹', 'Ã»', 'Ã¼', 'Ã§', 'Ã°', 'Ã±',
    'Ã', 'Ã‰', 'Ã€', 'Ã¡', 'Ã£', 'Ã¥'
]

found_any = False
for key, value in data.items():
    for pattern in mojibake_patterns:
        if pattern in value:
            print(f'MOJIBAKE in {key}: {value}')
            found_any = True
            break

if not found_any:
    print('No mojibake patterns found in any translation values')

# Print sample entries to verify
test_keys = ['login', 'logout', 'create', 'update', 'loan', 'loans', 'roles', 'settings', 'language', 'french', 'confirm_delete', 'province', 'Équateur', 'welcome', 'save', 'delete', 'email', 'password']
for key in test_keys:
    if key in data:
        print(f'  {key}: {data[key]}')