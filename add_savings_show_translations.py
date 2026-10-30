import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for savings show view
new_fr = {
    'transaction_details': 'Détails de la transaction',
    'back_to_list': 'Retour à la liste',
    'approve': 'Approuver',
    'reject': 'Rejeter',
    'print_receipt': 'Imprimer le reçu',
    'status': 'Statut',
    'approved_on': 'Approuvé le',
    'transaction_information': 'Informations sur la transaction',
    'transaction_number': 'N° transaction',
    'receipt_number': 'N° reçu',
    'not_generated_yet': 'Pas encore généré',
    'transaction_type': 'Type de transaction',
    'amount': 'Montant',
    'transaction_date': 'Date de transaction',
    'recorded_on': 'Enregistré le',
    'recorded_by': 'Enregistré par',
    'notes': 'Notes',
    'member_information': 'Informations du membre',
    'balance_before': 'Solde avant',
    'balance_after': 'Solde après',
    'view_member_profile': 'Voir le profil du membre',
    'recent_transactions_for': 'Transactions récentes pour',
    'date': 'Date',
    'type': 'Type',
    'balance': 'Solde',
    'no_other_transactions_found': 'Aucune autre transaction trouvée',
    'approve_transaction': 'Approuver la transaction',
    'sure_approve_transaction': 'Êtes-vous sûr de vouloir approuver cette transaction ?',
    'member': 'Membre',
    'cancel': 'Annuler',
    'reject_transaction': 'Rejeter la transaction',
    'rejection_reason': 'Motif du rejet',
    'rejection_reason_placeholder': 'Veuillez fournir un motif pour le rejet de cette transaction...',
}

new_en = {
    'transaction_details': 'Transaction Details',
    'back_to_list': 'Back to List',
    'approve': 'Approve',
    'reject': 'Reject',
    'print_receipt': 'Print Receipt',
    'status': 'Status',
    'approved_on': 'Approved on',
    'transaction_information': 'Transaction Information',
    'transaction_number': 'Transaction #',
    'receipt_number': 'Receipt #',
    'not_generated_yet': 'Not generated yet',
    'transaction_type': 'Transaction Type',
    'amount': 'Amount',
    'transaction_date': 'Transaction Date',
    'recorded_on': 'Recorded On',
    'recorded_by': 'Recorded By',
    'notes': 'Notes',
    'member_information': 'Member Information',
    'balance_before': 'Balance Before',
    'balance_after': 'Balance After',
    'view_member_profile': 'View Member Profile',
    'recent_transactions_for': 'Recent Transactions for',
    'date': 'Date',
    'type': 'Type',
    'balance': 'Balance',
    'no_other_transactions_found': 'No other transactions found',
    'approve_transaction': 'Approve Transaction',
    'sure_approve_transaction': 'Are you sure you want to approve this transaction?',
    'member': 'Member',
    'cancel': 'Cancel',
    'reject_transaction': 'Reject Transaction',
    'rejection_reason': 'Rejection Reason',
    'rejection_reason_placeholder': 'Please provide a reason for rejecting this transaction...',
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