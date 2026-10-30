import json

# Add status translations to both files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

status_translations_fr = {
    'active': 'Actif',
    'inactive': 'Inactif',
    'pending': 'En attente',
    'approved': 'Approuvé',
    'rejected': 'Rejeté',
    'disbursed': 'Décaissé',
    'paid': 'Payé',
    'overdue': 'En retard',
    'defaulted': 'En défaut',
    'completed': 'Terminé',
    'cancelled': 'Annulé',
    'processing': 'En cours',
    'verified': 'Vérifié',
    'unverified': 'Non vérifié',
    'submitted': 'Soumis',
    'under_review': 'En examen',
    'partially_paid': 'Partiellement payé',
    'fully_paid': 'Entièrement payé',
    'awaiting_approval': 'En attente d\'approbation',
    'awaiting_disbursement': 'En attente de décaissement',
}

for key, value in status_translations_fr.items():
    if key not in fr:
        fr[key] = value

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr, f, indent=4, ensure_ascii=False)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

status_translations_en = {
    'active': 'Active',
    'inactive': 'Inactive',
    'pending': 'Pending',
    'approved': 'Approved',
    'rejected': 'Rejected',
    'disbursed': 'Disbursed',
    'paid': 'Paid',
    'overdue': 'Overdue',
    'defaulted': 'Defaulted',
    'completed': 'Completed',
    'cancelled': 'Cancelled',
    'processing': 'Processing',
    'verified': 'Verified',
    'unverified': 'Unverified',
    'submitted': 'Submitted',
    'under_review': 'Under Review',
    'partially_paid': 'Partially Paid',
    'fully_paid': 'Fully Paid',
    'awaiting_approval': 'Awaiting Approval',
    'awaiting_disbursement': 'Awaiting Disbursement',
}

for key, value in status_translations_en.items():
    if key not in en:
        en[key] = value

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en, f, indent=4, ensure_ascii=False)

print('Added status translations to both files')