import json

# Add secure_connection to both files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

fr['secure_connection'] = 'Connexion sécurisée'

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

en['secure_connection'] = 'Secure Connection'

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, indent=4, ensure_ascii=False)

print('Added secure_connection to both files')