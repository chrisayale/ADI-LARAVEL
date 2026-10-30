import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for withdrawals view
new_fr = {
    'new_withdrawal': 'Nouveau retrait',
    'todays_withdrawals': 'Retraits du jour',
    'average_withdrawal': 'Retrait moyen',
    'filter_withdrawals': 'Filtrer les retraits',
    'withdrawal_transactions': 'Transactions de retrait',
    'no_withdrawals_found': 'Aucun retrait trouvé',
}

new_en = {
    'new_withdrawal': 'New Withdrawal',
    'todays_withdrawals': "Today's Withdrawals",
    'average_withdrawal': 'Average Withdrawal',
    'filter_withdrawals': 'Filter Withdrawals',
    'withdrawal_transactions': 'Withdrawal Transactions',
    'no_withdrawals_found': 'No withdrawals found',
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