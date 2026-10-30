import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for approved loans view
new_fr = {
    'approved_loans': 'Prêts approuvés',
    'total_approved': 'Total approuvé',
    'total_amount': 'Montant total',
    'awaiting_disbursement': 'En attente de décaissement',
    'total_interest': 'Total des intérêts',
    'filter_approved_loans': 'Filtrer les prêts approuvés',
    'search_member': 'Rechercher un membre',
    'search_member_placeholder': 'Nom ou numéro de membre...',
    'approval_date': 'Date d\'approbation',
    'all_time': 'Tout le temps',
    'today': 'Aujourd\'hui',
    'this_week': 'Cette semaine',
    'this_month': 'Ce mois',
    'this_quarter': 'Ce trimestre',
    'this_year': 'Cette année',
    'approved_loans_list': 'Liste des prêts approuvés',
    'application_no': 'N° demande',
    'member': 'Membre',
    'membership_no': 'N° adhésion',
    'amount_approved': 'Montant approuvé',
    'interest_rate': 'Taux d\'intérêt',
    'duration': 'Durée',
    'months': 'mois',
    'monthly_payment': 'Paiement mensuel',
    'total_repayable': 'Total remboursable',
    'approved_date': 'Date d\'approbation',
    'approved_by': 'Approuvé par',
    'actions': 'Actions',
    'view_details': 'Voir les détails',
    'disburse_loan': 'Décaisser le prêt',
    'confirm_disburse': 'Êtes-vous sûr de vouloir décaisser ce prêt ?',
    'no_approved_loans_found': 'Aucun prêt approuvé trouvé',
    'back_to_dashboard': 'Retour au tableau de bord',
}

new_en = {
    'approved_loans': 'Approved Loans',
    'total_approved': 'Total Approved',
    'total_amount': 'Total Amount',
    'awaiting_disbursement': 'Awaiting Disbursement',
    'total_interest': 'Total Interest',
    'filter_approved_loans': 'Filter Approved Loans',
    'search_member': 'Search Member',
    'search_member_placeholder': 'Name or Membership Number...',
    'approval_date': 'Approval Date',
    'all_time': 'All Time',
    'today': 'Today',
    'this_week': 'This Week',
    'this_month': 'This Month',
    'this_quarter': 'This Quarter',
    'this_year': 'This Year',
    'approved_loans_list': 'Approved Loans List',
    'application_no': 'Application No.',
    'member': 'Member',
    'membership_no': 'Membership No.',
    'amount_approved': 'Amount Approved',
    'interest_rate': 'Interest Rate',
    'duration': 'Duration',
    'months': 'months',
    'monthly_payment': 'Monthly Payment',
    'total_repayable': 'Total Repayable',
    'approved_date': 'Approved Date',
    'approved_by': 'Approved By',
    'actions': 'Actions',
    'view_details': 'View Details',
    'disburse_loan': 'Disburse Loan',
    'confirm_disburse': 'Are you sure you want to disburse this loan?',
    'no_approved_loans_found': 'No approved loans found',
    'back_to_dashboard': 'Back to Dashboard',
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