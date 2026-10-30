import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for savings create view
new_fr = {
    'record_savings_transaction': 'Enregistrer une transaction d\'épargne',
    'back_to_list': 'Retour à la liste',
    'validation_errors': 'Erreurs de validation',
    'select_member': 'Sélectionner un membre',
    'search_member': 'Rechercher un membre',
    'select_a_member': 'Sélectionner un membre...',
    'no_number': 'Aucun #',
    'member_details': 'Détails du membre',
    'name': 'Nom',
    'membership_number': 'N° adhésion',
    'email': 'Email',
    'phone': 'Téléphone',
    'current_balance': 'Solde actuel',
    'n_a': 'N/A',
    'recent_transactions': 'Transactions récentes',
    'date': 'Date',
    'type': 'Type',
    'amount': 'Montant',
    'balance': 'Solde',
    'no_recent_transactions': 'Aucune transaction récente',
    'transaction_details': 'Détails de la transaction',
    'transaction_type': 'Type de transaction',
    'deposit': 'Dépôt',
    'withdrawal': 'Retrait',
    'amount_ugx': 'Montant (UGX)',
    'transaction_date': 'Date de transaction',
    'status': 'Statut',
    'complete_now': 'Terminer maintenant',
    'requires_approval': 'Nécessite approbation',
    'select_pending_if_review': 'Sélectionnez "En attente" si la transaction doit être revue avant approbation',
    'notes_description': 'Notes / Description',
    'transaction_summary': 'Résumé de la transaction',
    'transaction_amount': 'Montant de la transaction',
    'new_balance': 'Nouveau solde',
    'reset': 'Réinitialiser',
    'record_transaction': 'Enregistrer la transaction',
    'search_for_a_member': 'Rechercher un membre...',
    'failed_to_load_member_details': 'Échec du chargement des détails du membre.',
    'please_select_valid_member': 'Veuillez sélectionner un membre valide d\'abord.',
    'insufficient_balance': 'Solde insuffisant',
    'please_select_member_first': 'Veuillez d\'abord sélectionner un membre.',
}

new_en = {
    'record_savings_transaction': 'Record Savings Transaction',
    'back_to_list': 'Back to List',
    'validation_errors': 'Validation Errors',
    'select_member': 'Select Member',
    'search_member': 'Search Member',
    'select_a_member': 'Select a member...',
    'no_number': 'No #',
    'member_details': 'Member Details',
    'name': 'Name',
    'membership_number': 'Membership #',
    'email': 'Email',
    'phone': 'Phone',
    'current_balance': 'Current Balance',
    'n_a': 'N/A',
    'recent_transactions': 'Recent Transactions',
    'date': 'Date',
    'type': 'Type',
    'amount': 'Amount',
    'balance': 'Balance',
    'no_recent_transactions': 'No recent transactions',
    'transaction_details': 'Transaction Details',
    'transaction_type': 'Transaction Type',
    'deposit': 'Deposit',
    'withdrawal': 'Withdrawal',
    'amount_ugx': 'Amount (UGX)',
    'transaction_date': 'Transaction Date',
    'status': 'Status',
    'complete_now': 'Complete Now',
    'requires_approval': 'Requires Approval',
    'select_pending_if_review': 'Select "Pending" if transaction needs review before approval',
    'notes_description': 'Notes / Description',
    'transaction_summary': 'Transaction Summary',
    'transaction_amount': 'Transaction Amount',
    'new_balance': 'New Balance',
    'reset': 'Reset',
    'record_transaction': 'Record Transaction',
    'search_for_a_member': 'Search for a member...',
    'failed_to_load_member_details': 'Failed to load member details.',
    'please_select_valid_member': 'Please select a valid member first.',
    'insufficient_balance': 'Insufficient balance',
    'please_select_member_first': 'Please select a member first.',
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