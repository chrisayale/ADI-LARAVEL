import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for sacco requests index view
new_fr = {
    'sacco_requests': 'Demandes de SACCO',
    'filter_by_status': 'Filtrer par statut',
    'sacco_requests_list': 'Liste des demandes de SACCO',
    'sacco_name': 'Nom du SACCO',
    'applicant': 'Demandeur',
    'location': 'Localisation',
    'mobile_money_phone': 'Téléphone Mobile Money',
    'submission_date': 'Date de soumission',
    'no_sacco_requests_found': 'Aucune demande de SACCO trouvée',
    'confirm_toggle_status': 'Êtes-vous sûr de vouloir :action ce SACCO ?',
}

new_en = {
    'sacco_requests': 'SACCO Requests',
    'filter_by_status': 'Filter by Status',
    'sacco_requests_list': 'SACCO Requests List',
    'sacco_name': 'SACCO Name',
    'applicant': 'Applicant',
    'location': 'Location',
    'mobile_money_phone': 'Mobile Money Phone',
    'submission_date': 'Submission Date',
    'no_sacco_requests_found': 'No SACCO requests found',
    'confirm_toggle_status': 'Are you sure you want to :action this SACCO?',
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
