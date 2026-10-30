import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for loan applications view
new_fr = {
    'manage_loan_applications': 'Gérer les demandes de prêt',
    'loan_applications': 'Demandes de prêt',
    'all_applications': 'Toutes les demandes',
    'approved': 'Approuvé',
    'disbursed': 'Décaissé',
    'defaulters': 'Défaut de paiement',
    'filter_loans': 'Filtrer les prêts',
    'search_member': 'Rechercher un membre',
    'search_member_placeholder': 'Nom ou numéro de membre...',
    'date_from': 'Date de début',
    'total_loans': 'Total des prêts',
    'approved_loans': 'Prêts approuvés',
    'disbursed_loans': 'Prêts décaissés',
    'loan_applications_list': 'Liste des demandes de prêt',
    'member': 'Membre',
    'membership_no': 'N° adhésion',
    'amount_applied': 'Montant demandé',
    'amount_approved': 'Montant approuvé',
    'interest_rate': 'Taux d\'intérêt',
    'duration': 'Durée',
    'months': 'mois',
    'treasurer': 'Trésorier',
    'applied_date': 'Date de demande',
    'no_loan_applications_found': 'Aucune demande de prêt trouvée',
    'not_assigned': 'Non assigné',
    'assign_treasurer': 'Assigner un trésorier',
    'select_treasurer': 'Sélectionner un trésorier',
    'choose_treasurer': 'Choisir un trésorier...',
    'assign': 'Assigner',
    'view_details': 'Voir les détails',
    'completed': 'Terminé',
    'defaulted': 'En défaut',
    'under_verification': 'En vérification',
    'all_status': 'Tous les statuts',
}

new_en = {
    'manage_loan_applications': 'Manage Loan Applications',
    'loan_applications': 'Loan Applications',
    'all_applications': 'All Applications',
    'approved': 'Approved',
    'disbursed': 'Disbursed',
    'defaulters': 'Defaulters',
    'filter_loans': 'Filter Loans',
    'search_member': 'Search Member',
    'search_member_placeholder': 'Name or Membership Number...',
    'date_from': 'Date From',
    'total_loans': 'Total Loans',
    'approved_loans': 'Approved Loans',
    'disbursed_loans': 'Disbursed Loans',
    'loan_applications_list': 'Loan Applications List',
    'member': 'Member',
    'membership_no': 'Membership No.',
    'amount_applied': 'Amount Applied',
    'amount_approved': 'Amount Approved',
    'interest_rate': 'Interest Rate',
    'duration': 'Duration',
    'months': 'months',
    'treasurer': 'Treasurer',
    'applied_date': 'Applied Date',
    'no_loan_applications_found': 'No loan applications found',
    'not_assigned': 'Not Assigned',
    'assign_treasurer': 'Assign Treasurer',
    'select_treasurer': 'Select Treasurer',
    'choose_treasurer': 'Choose Treasurer...',
    'assign': 'Assign',
    'view_details': 'View Details',
    'completed': 'Completed',
    'defaulted': 'Defaulted',
    'under_verification': 'Under Verification',
    'all_status': 'All Status',
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