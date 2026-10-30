import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for under verification view
new_fr = {
    'loan_applications_under_verification': 'Demandes de prêt en vérification',
    'all_treasurers': 'Tous les trésoriers',
    'application_number': 'N° demande',
    'member': 'Membre',
    'amount_applied': 'Montant demandé',
    'treasurer': 'Trésorier',
    'assigned_date': 'Date d\'assignation',
    'actions': 'Actions',
    'not_assigned': 'Non assigné',
    'view': 'Voir',
    'no_loans_under_verification_found': 'Aucun prêt en vérification trouvé.',
}

new_en = {
    'loan_applications_under_verification': 'Loan Applications Under Verification',
    'all_treasurers': 'All Treasurers',
    'application_number': 'Application #',
    'member': 'Member',
    'amount_applied': 'Amount Applied',
    'treasurer': 'Treasurer',
    'assigned_date': 'Assigned Date',
    'actions': 'Actions',
    'not_assigned': 'Not Assigned',
    'view': 'View',
    'no_loans_under_verification_found': 'No loans under verification.',
}

fr.update(new_fr)
en.update(new_en)

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, indent=4, ensure_ascii=False)

print('Added translation keys to both files')
print(f'fr.json: {len(fr)} keys')
print(f'en.json: {len(en)} keys')