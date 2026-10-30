import json

with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)
with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Check savings key
print('savings in fr:', fr.get('savings', 'MISSING'))
print('savings in en:', en.get('savings', 'MISSING'))

# Check all_loans, applications
print('all_loans in fr:', fr.get('all_loans', 'MISSING'))
print('all_loans in en:', en.get('all_loans', 'MISSING'))
print('applications in fr:', fr.get('applications', 'MISSING'))
print('applications in en:', en.get('applications', 'MISSING'))

# Check Active/Actif
print('active in fr:', fr.get('active', 'MISSING'))
print('active in en:', en.get('active', 'MISSING'))

# Check footer strings
for key in ['not_secure', 'last_login', 'first_login']:
    print(f'{key} in fr:', fr.get(key, 'MISSING'))
    print(f'{key} in en:', en.get(key, 'MISSING'))