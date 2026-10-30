import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for loan applications index view
new_fr = {
    'all_loan_applications': 'Toutes les demandes de prêt',
    'new_loan_application': 'Nouvelle demande de prêt',
    'back_to_dashboard': 'Retour au tableau de bord',
    'total_applications': 'Total des demandes',
    'total_amount': 'Montant total',
    'filter_applications': 'Filtrer les demandes',
    'search_member': 'Rechercher un membre',
    'search_member_placeholder': 'Nom ou numéro de membre...',
    'application_status': 'Statut de la demande',
    'date_range': 'Plage de dates',
    'all_status': 'Tous les statuts',
    'all_time': 'Tout le temps',
    'today': 'Aujourd\'hui',
    'this_week': 'Cette semaine',
    'this_month': 'Ce mois',
    'this_quarter': 'Ce trimestre',
    'this_year': 'Cette année',
    'applications_list': 'Liste des demandes',
    'application_no': 'N° demande',
    'purpose': 'Objet',
    'verified': 'Vérifié',
    'rejected': 'Rejeté',
    'completed': 'Terminé',
    'defaulted': 'En défaut',
    'not_assigned': 'Non assigné',
    'assign_treasurer': 'Assigner un trésorier',
    'select_treasurer': 'Sélectionner un trésorier',
    'choose_treasurer': 'Choisir un trésorier...',
    'assign': 'Assigner',
    'view_details': 'Voir les détails',
    'disburse_loan': 'Décaisser le prêt',
    'confirm_disburse': 'Êtes-vous sûr de vouloir décaisser ce prêt ?',
    'no_applications_found': 'Aucune demande trouvée',
}

new_en = {
    'all_loan_applications': 'All Loan Applications',
    'new_loan_application': 'New Loan Application',
    'back_to_dashboard': 'Back to Dashboard',
    'total_applications': 'Total Applications',
    'total_amount': 'Total Amount',
    'filter_applications': 'Filter Applications',
    'search_member': 'Search Member',
    'search_member_placeholder': 'Name or Membership Number...',
    'application_status': 'Application Status',
    'date_range': 'Date Range',
    'all_status': 'All Status',
    'all_time': 'All Time',
    'today': 'Today',
    'this_week': 'This Week',
    'this_month': 'This Month',
    'this_quarter': 'This Quarter',
    'this_year': 'This Year',
    'applications_list': 'Applications List',
    'application_no': 'Application No.',
    'purpose': 'Purpose',
    'verified': 'Verified',
    'rejected': 'Rejected',
    'completed': 'Completed',
    'defaulted': 'Defaulted',
    'not_assigned': 'Not Assigned',
    'assign_treasurer': 'Assign Treasurer',
    'select_treasurer': 'Select Treasurer',
    'choose_treasurer': 'Choose Treasurer...',
    'assign': 'Assign',
    'view_details': 'View Details',
    'disburse_loan': 'Disburse Loan',
    'confirm_disburse': 'Are you sure you want to disburse this loan?',
    'no_applications_found': 'No applications found',
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