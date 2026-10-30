import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for savings transactions view
new_fr = {
    'savings_transactions': 'Transactions d\'épargne',
    'new_transaction': 'Nouvelle transaction',
    'deposits': 'Dépôts',
    'withdrawals': 'Retraits',
    'total_deposits': 'Total dépôts',
    'total_withdrawals': 'Total retraits',
    'pending_transactions': 'Transactions en attente',
    'todays_total': 'Total du jour',
    'transactions': 'transactions',
    'filter_transactions': 'Filtrer les transactions',
    'search_member': 'Rechercher un membre',
    'name_or_membership': 'Nom ou N° adhésion',
    'transaction_type': 'Type de transaction',
    'all_types': 'Tous les types',
    'deposit': 'Dépôt',
    'withdrawal': 'Retrait',
    'status': 'Statut',
    'all_status': 'Tous les statuts',
    'date_from': 'Date de début',
    'date_to': 'Date de fin',
    'all_transactions': 'Toutes les transactions',
    'transaction_number': 'N° transaction',
    'member': 'Membre',
    'type': 'Type',
    'amount': 'Montant',
    'balance_after': 'Solde après',
    'date': 'Date',
    'recorded_by': 'Enregistré par',
    'actions': 'Actions',
    'receipt': 'Reçu',
    'view': 'Voir',
    'print_receipt': 'Imprimer le reçu',
    'no_transactions_found': 'Aucune transaction trouvée',
}

new_en = {
    'savings_transactions': 'Savings Transactions',
    'new_transaction': 'New Transaction',
    'deposits': 'Deposits',
    'withdrawals': 'Withdrawals',
    'total_deposits': 'Total Deposits',
    'total_withdrawals': 'Total Withdrawals',
    'pending_transactions': 'Pending Transactions',
    'todays_total': "Today's Total",
    'transactions': 'transactions',
    'filter_transactions': 'Filter Transactions',
    'search_member': 'Search Member',
    'name_or_membership': 'Name or Membership #',
    'transaction_type': 'Transaction Type',
    'all_types': 'All Types',
    'deposit': 'Deposit',
    'withdrawal': 'Withdrawal',
    'status': 'Status',
    'all_status': 'All Status',
    'date_from': 'Date From',
    'date_to': 'Date To',
    'all_transactions': 'All Transactions',
    'transaction_number': 'Transaction #',
    'member': 'Member',
    'type': 'Type',
    'amount': 'Amount',
    'balance_after': 'Balance After',
    'date': 'Date',
    'recorded_by': 'Recorded By',
    'actions': 'Actions',
    'receipt': 'Receipt',
    'view': 'View',
    'print_receipt': 'Print Receipt',
    'no_transactions_found': 'No transactions found',
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