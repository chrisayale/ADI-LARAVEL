import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for savings receipt view
new_fr = {
    'receipt': 'Reçu',
    'savings_transaction_receipt': 'Reçu de transaction d\'épargne',
    'your_trusted_financial_partner': 'Votre partenaire financier de confiance',
    'contact_info': 'Tél: +256 XXX XXX XXX | Email: info@capitalinvestment.club',
    'deposit_receipt': 'REÇU DE DÉPÔT',
    'withdrawal_receipt': 'REÇU DE RETRAIT',
    'receipt_number': 'N° reçu',
    'transaction_number': 'N° transaction',
    'date': 'Date',
    'time': 'Heure',
    'member_name': 'Nom du membre',
    'membership_number': 'N° adhésion',
    'phone': 'Téléphone',
    'transaction_type': 'Type de transaction',
    'amount': 'Montant',
    'balance_after': 'Solde après',
    'notes': 'Notes',
    'recorded_by': 'Enregistré par',
    'approved_by': 'Approuvé par',
    'system': 'Système',
    'authorized_signature': 'Signature autorisée',
    'computer_generated_receipt': 'Ce reçu est généré par ordinateur',
    'thank_you_for_banking': 'Merci d\'avoir fait affaire avec nous !',
    'powered_by_sacco_system': 'Propulsé par le système Sacco Capital Investment Club',
    'print_receipt': 'Imprimer le reçu',
    'close': 'Fermer',
}

new_en = {
    'receipt': 'Receipt',
    'savings_transaction_receipt': 'Savings Transaction Receipt',
    'your_trusted_financial_partner': 'Your Trusted Financial Partner',
    'contact_info': 'Tel: +256 XXX XXX XXX | Email: info@capitalinvestment.club',
    'deposit_receipt': 'DEPOSIT RECEIPT',
    'withdrawal_receipt': 'WITHDRAWAL RECEIPT',
    'receipt_number': 'Receipt Number',
    'transaction_number': 'Transaction #',
    'date': 'Date',
    'time': 'Time',
    'member_name': 'Member Name',
    'membership_number': 'Membership #',
    'phone': 'Phone',
    'transaction_type': 'Transaction Type',
    'amount': 'Amount',
    'balance_after': 'Balance After',
    'notes': 'Notes',
    'recorded_by': 'Recorded By',
    'approved_by': 'Approved By',
    'system': 'System',
    'authorized_signature': 'Authorized Signature',
    'computer_generated_receipt': 'This is a computer generated receipt',
    'thank_you_for_banking': 'Thank you for banking with us!',
    'powered_by_sacco_system': 'Powered by Capital Investment Club Sacco System',
    'print_receipt': 'Print Receipt',
    'close': 'Close',
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