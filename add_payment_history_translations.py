import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for payment history view
new_fr = {
    'payment_history': 'Historique des paiements',
    'new_payment': 'Nouveau paiement',
    'todays_collections': 'Encaissements du jour',
    'total_transactions': 'Total transactions',
    'showing': 'Affichés',
    'filter_payments': 'Filtrer les paiements',
    'date_from': 'Date de début',
    'date_to': 'Date de fin',
    'all_members': 'Tous les membres',
    'all_methods': 'Toutes les méthodes',
    'apply_filters': 'Appliquer les filtres',
    'clear': 'Effacer',
    'receipt_number': 'N° reçu',
    'date': 'Date',
    'member': 'Membre',
    'loan': 'Prêt',
    'amount': 'Montant',
    'reference_number': 'Numéro de référence',
    'collected_by': 'Encaissé par',
    'actions': 'Actions',
    'installment': 'Échéance',
    'n_a': 'N/A',
    'print_receipt': 'Imprimer le reçu',
    'no_payment_transactions_found': 'Aucune transaction de paiement trouvée',
    'cash': 'Espèces',
    'bank_transfer': 'Virement bancaire',
    'mobile_money': 'Mobile Money',
}

new_en = {
    'payment_history': 'Payment History',
    'new_payment': 'New Payment',
    'todays_collections': "Today's Collections",
    'total_transactions': 'Total Transactions',
    'showing': 'Showing',
    'filter_payments': 'Filter Payments',
    'date_from': 'Date From',
    'date_to': 'Date To',
    'all_members': 'All Members',
    'all_methods': 'All Methods',
    'apply_filters': 'Apply Filters',
    'clear': 'Clear',
    'receipt_number': 'Receipt #',
    'date': 'Date',
    'member': 'Member',
    'loan': 'Loan',
    'amount': 'Amount',
    'reference_number': 'Reference Number',
    'collected_by': 'Collected By',
    'actions': 'Actions',
    'installment': 'Installment',
    'n_a': 'N/A',
    'print_receipt': 'Print Receipt',
    'no_payment_transactions_found': 'No payment transactions found',
    'cash': 'Cash',
    'bank_transfer': 'Bank Transfer',
    'mobile_money': 'Mobile Money',
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