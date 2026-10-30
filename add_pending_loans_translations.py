import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for pending loan applications view
new_fr = {
    'pending_loan_applications': 'Demandes de prêt en attente',
    'new_loan_application': 'Nouvelle demande de prêt',
    'total_pending_applications': 'Total demandes en attente',
    'total_amount_applied': 'Montant total demandé',
    'search_member_placeholder': 'Rechercher par nom ou numéro de membre...',
    'search': 'Rechercher',
    'clear': 'Effacer',
    'application_number': 'N° demande',
    'member': 'Membre',
    'membership_number': 'N° adhésion',
    'amount_applied': 'Montant demandé',
    'purpose': 'Objet',
    'applied_date': 'Date de demande',
    'actions': 'Actions',
    'view': 'Voir',
    'no_pending_loan_applications_found': 'Aucune demande de prêt en attente trouvée.',
}

new_en = {
    'pending_loan_applications': 'Pending Loan Applications',
    'new_loan_application': 'New Loan Application',
    'total_pending_applications': 'Total Pending Applications',
    'total_amount_applied': 'Total Amount Applied',
    'search_member_placeholder': 'Search by member name or membership number...',
    'search': 'Search',
    'clear': 'Clear',
    'application_number': 'Application #',
    'member': 'Member',
    'membership_number': 'Membership #',
    'amount_applied': 'Amount Applied',
    'purpose': 'Purpose',
    'applied_date': 'Applied Date',
    'actions': 'Actions',
    'view': 'View',
    'no_pending_loan_applications_found': 'No pending loan applications found.',
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