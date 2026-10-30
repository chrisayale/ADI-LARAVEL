import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for payments receipt view
new_fr = {
    'payment_receipt': 'Reçu de paiement',
    'receipt_number': 'N° reçu',
    'date': 'Date',
    'member': 'Membre',
    'membership_number': 'N° adhésion',
    'loan': 'Prêt',
    'installment': 'Échéance',
    'payment_method': 'Mode de paiement',
    'reference_number': 'Numéro de référence',
    'cash': 'Espèces',
    'bank_transfer': 'Virement bancaire',
    'mobile_money': 'Mobile Money',
    'amount_paid': 'Montant payé',
    'notes': 'Notes',
    'received_by': 'Reçu par',
    'signature': 'Signature',
    'computer_generated_receipt_no_signature': 'Ceci est un reçu généré par ordinateur. Aucune signature requise.',
    'thank_you_for_payment': 'Merci pour votre paiement !',
    'print_receipt': 'Imprimer le reçu',
    'go_back': 'Retour',
}

new_en = {
    'payment_receipt': 'Payment Receipt',
    'receipt_number': 'Receipt #',
    'date': 'Date',
    'member': 'Member',
    'membership_number': 'Membership #',
    'loan': 'Loan',
    'installment': 'Installment',
    'payment_method': 'Payment Method',
    'reference_number': 'Reference Number',
    'cash': 'Cash',
    'bank_transfer': 'Bank Transfer',
    'mobile_money': 'Mobile Money',
    'amount_paid': 'Amount Paid',
    'notes': 'Notes',
    'received_by': 'Received By',
    'signature': 'Signature',
    'computer_generated_receipt_no_signature': 'This is a computer generated receipt. No signature required.',
    'thank_you_for_payment': 'Thank you for your payment!',
    'print_receipt': 'Print Receipt',
    'go_back': 'Go Back',
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