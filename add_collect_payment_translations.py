import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for collect payment view
new_fr = {
    'collect_loan_payment': 'Encaisser un paiement de prêt',
    'search_payment_member_placeholder': 'Rechercher par numéro d\'adhésion, téléphone ou nom...',
    'find_member': 'Trouver le membre',
    'membership_number': 'N° adhésion',
    'phone': 'Téléphone',
    'refresh': 'Actualiser',
    'no_active_loans_found_for_member': 'Aucun prêt actif trouvé pour ce membre.',
    'loan': 'Prêt',
    'balance': 'Solde',
    'installment': 'Échéance',
    'due_date': 'Date d\'échéance',
    'amount_due': 'Montant dû',
    'amount_paid': 'Montant payé',
    'status': 'Statut',
    'process_payment': 'Traiter le paiement',
    'selected_installments': 'Échéances sélectionnées',
    'total_amount_due': 'Total dû',
    'payment_amount': 'Montant du paiement',
    'payment_method': 'Mode de paiement',
    'cash': 'Espèces',
    'bank_transfer': 'Virement bancaire',
    'mobile_money': 'Mobile Money',
    'reference_number': 'Numéro de référence',
    'optional': 'Optionnel',
    'notes': 'Notes',
    'any_additional_notes': 'Toutes notes additionnelles',
    'search_member_to_collect': 'Rechercher un membre pour encaisser un paiement',
    'enter_membership_number_above': 'Entrez le numéro d\'adhésion, le téléphone ou le nom ci-dessus',
    'no_member_found_with_search': 'Aucun membre trouvé avec',
    'please_try_again': 'Veuillez réessayer',
}

new_en = {
    'collect_loan_payment': 'Collect Loan Payment',
    'search_payment_member_placeholder': 'Search by Membership Number, Phone Number, or Name...',
    'find_member': 'Find Member',
    'membership_number': 'Membership #',
    'phone': 'Phone',
    'refresh': 'Refresh',
    'no_active_loans_found_for_member': 'No active loans found for this member.',
    'loan': 'Loan',
    'balance': 'Balance',
    'installment': 'Installment',
    'due_date': 'Due Date',
    'amount_due': 'Amount Due',
    'amount_paid': 'Amount Paid',
    'status': 'Status',
    'process_payment': 'Process Payment',
    'selected_installments': 'Selected Installments',
    'total_amount_due': 'Total Amount Due',
    'payment_amount': 'Payment Amount',
    'payment_method': 'Payment Method',
    'cash': 'Cash',
    'bank_transfer': 'Bank Transfer',
    'mobile_money': 'Mobile Money',
    'reference_number': 'Reference Number',
    'optional': 'Optional',
    'notes': 'Notes',
    'any_additional_notes': 'Any additional notes',
    'search_member_to_collect': 'Search for a member to collect payment',
    'enter_membership_number_above': 'Enter membership number, phone number, or name above',
    'no_member_found_with_search': 'No member found with',
    'please_try_again': 'Please try again.',
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