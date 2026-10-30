# Fix fr.json encoding
import json

with open('lang/fr.json', 'r', encoding='utf-8') as f:
    text = f.read()

# Fix known mojibake patterns
fixes = {
    'Ã©': 'é',
    'Ã¨': 'è',
    'Ãª': 'ê',
    'Ã«': 'ë',
    'Ã ': 'à',
    'Ã¢': 'â',
    'Ã¤': 'ä',
    'Ã®': 'î',
    'Ã¯': 'ï',
    'Ã´': 'ô',
    'Ã¶': 'ö',
    'Ã¹': 'ù',
    'Ã»': 'û',
    'Ã¼': 'ü',
    'Ã§': 'ç',
    'Ã°': 'ð',
    'Ã±': 'ñ',
    'Ã': 'Ê',
    'Ã‰': 'É',
    'Ã€': 'À',
    'Ã¡': 'á',
    'Ã¢': 'â',
    'Ã£': 'ã',
    'Ã¥': 'å',
}

fixed_text = text
for mojibake, correct in fixes.items():
    fixed_text = fixed_text.replace(mojibake, correct)

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    f.write(fixed_text)

print('Fixed mojibake patterns and rewrote file')

# Verify
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    data = json.load(f)
print(f'Valid JSON with {len(data)} keys')

test_keys = ['login', 'logout', 'create', 'update', 'loan', 'loans', 'roles', 'settings', 'language', 'french', 'confirm_delete']
for key in test_keys:
    if key in data:
        print(f'  {key}: {data[key]}')