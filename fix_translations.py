import json

# Fix fr.json
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

# Fix the savings encoding issue (Ãpargne -> Épargne)
fr['savings'] = 'Épargne'

# Add missing translations
fr.update({
    'all_loans': 'Tous les prêts',
    'applications': 'Demandes de prêt',
    'not_secure': 'Non sécurisé',
    'last_login': 'Dernière connexion : ',
    'first_login': 'Première connexion',
})

# Also fix membership_no, application_no, loan_no encoding (NÂ° -> N°)
fr['membership_no'] = "N° d'adhésion"
fr['application_no'] = 'N° demande'
fr['loan_no'] = 'N° prêt'

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

print('fr.json updated')

# Fix en.json - add missing translations
with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

en.update({
    'all_loans': 'All Loans',
    'applications': 'Loan Applications',
    'not_secure': 'Not Secure',
    'last_login': 'Last login: ',
    'first_login': 'First login',
})

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, indent=4, ensure_ascii=False)

print('en.json updated')

# Verify
print('\nVerification:')
for lang_name, data in [('fr', fr), ('en', en)]:
    for key in ['savings', 'all_loans', 'applications', 'active', 'not_secure', 'last_login', 'first_login', 'membership_no', 'application_no', 'loan_no']:
        val = data.get(key, 'MISSING')
        with open('verify.txt', 'a', encoding='utf-8') as out:
            out.write(f'{lang_name}.{key} = {val}\n')