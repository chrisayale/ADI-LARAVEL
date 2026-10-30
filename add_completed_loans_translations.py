import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for completed loans view
new_fr = {
    'completed_loans': 'Prêts terminés',
    'total_completed_loans': 'Total prêts terminés',
    'total_amount_repaid': 'Montant total remboursé',
    'amount_approved': 'Montant approuvé',
    'total_repaid': 'Total remboursé',
    'completion_date': 'Date de fin',
    'no_completed_loans_found': 'Aucun prêt terminé trouvé.',
}

new_en = {
    'completed_loans': 'Completed Loans',
    'total_completed_loans': 'Total Completed Loans',
    'total_amount_repaid': 'Total Amount Repaid',
    'amount_approved': 'Amount Approved',
    'total_repaid': 'Total Repaid',
    'completion_date': 'Completion Date',
    'no_completed_loans_found': 'No completed loans found.',
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