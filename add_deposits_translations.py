import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for deposits view
new_fr = {
    'new_deposit': 'Nouveau dépôt',
    'todays_deposits': 'Dépôts du jour',
    'average_deposit': 'Dépôt moyen',
    'filter_deposits': 'Filtrer les dépôts',
    'deposit_transactions': 'Transactions de dépôt',
    'no_deposits_found': 'Aucun dépôt trouvé',
}

new_en = {
    'new_deposit': 'New Deposit',
    'todays_deposits': "Today's Deposits",
    'average_deposit': 'Average Deposit',
    'filter_deposits': 'Filter Deposits',
    'deposit_transactions': 'Deposit Transactions',
    'no_deposits_found': 'No deposits found',
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