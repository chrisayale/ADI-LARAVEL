import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for verified loan applications view
new_fr = {
    'verified_loan_applications_ready': 'Demandes de prêt vérifiées',
    'back_to_all_loans': 'Retour à tous les prêts',
    'total_verified_applications': 'Total demandes vérifiées',
    'total_amount_applied': 'Montant total demandé',
    'average_loan_amount': 'Montant moyen des prêts',
    'search_verified_placeholder': 'Rechercher par nom, numéro de membre ou numéro de demande...',
    'search': 'Rechercher',
    'clear_filters': 'Effacer les filtres',
    'advanced_filters': 'Filtres avancés',
    'date_from': 'Date de début',
    'date_to': 'Date de fin',
    'min_amount': 'Montant minimum',
    'max_amount': 'Montant maximum',
    'apply_filters': 'Appliquer les filtres',
    'application_number': 'N° demande',
    'member_details': 'Détails du membre',
    'loan_details': 'Détails du prêt',
    'verification_info': 'Informations de vérification',
    'guarantors': 'Garants',
    'documents': 'Documents',
    'actions': 'Actions',
    'applied': 'Demandé le',
    'n_a': 'N/A',
    'verified_by': 'Vérifié par',
    'notes': 'Notes',
    'all_verified': 'Tous vérifiés',
    'files': 'fichiers',
    'verified': 'Vérifiés',
    'no_verified_loan_applications_found': 'Aucune demande de prêt vérifiée trouvée.',
    'verified_loans_will_appear': 'Les prêts vérifiés par les trésoriers apparaîtront ici.',
    'view_details': 'Voir les détails',
    'approve_loan_application': 'Approuver la demande de prêt',
    'approved_amount': 'Montant approuvé',
    'maximum_amount': 'Montant maximum',
    'interest_rate': 'Taux d\'intérêt',
    'duration_months': 'Durée (mois)',
    'approval_notes': 'Notes d\'approbation',
    'approval_notes_placeholder': 'Notes d\'approbation (optionnel)...',
    'loan_summary_calculated': 'Résumé du prêt (calculé)',
    'monthly_payment': 'Paiement mensuel',
    'total_interest': 'Intérêt total',
    'total_repayable': 'Total à rembourser',
    'cancel': 'Annuler',
    'approve_loan': 'Approuver le prêt',
    'reject_loan_application': 'Rejeter la demande de prêt',
    'reject_action_warning': 'Cette action rejette la demande de prêt. Le membre peut faire une nouvelle demande.',
    'rejection_reason': 'Motif du rejet',
    'rejection_reason_placeholder': 'Expliquez le motif du rejet...',
    'reject_loan': 'Rejeter le prêt',
    'view_documents': 'Voir les documents',
}

new_en = {
    'verified_loan_applications_ready': 'Verified Loan Applications',
    'back_to_all_loans': 'Back to All Loans',
    'total_verified_applications': 'Total Verified Applications',
    'total_amount_applied': 'Total Amount Applied',
    'average_loan_amount': 'Average Loan Amount',
    'search_verified_placeholder': 'Search by name, membership number or application number...',
    'search': 'Search',
    'clear_filters': 'Clear Filters',
    'advanced_filters': 'Advanced Filters',
    'date_from': 'Date From',
    'date_to': 'Date To',
    'min_amount': 'Min Amount',
    'max_amount': 'Max Amount',
    'apply_filters': 'Apply Filters',
    'application_number': 'Application #',
    'member_details': 'Member Details',
    'loan_details': 'Loan Details',
    'verification_info': 'Verification Info',
    'guarantors': 'Guarantors',
    'documents': 'Documents',
    'actions': 'Actions',
    'applied': 'Applied',
    'n_a': 'N/A',
    'verified_by': 'Verified By',
    'notes': 'Notes',
    'all_verified': 'All Verified',
    'files': 'Files',
    'verified': 'Verified',
    'no_verified_loan_applications_found': 'No verified loan applications found.',
    'verified_loans_will_appear': 'Loans verified by treasurers will appear here.',
    'view_details': 'View Details',
    'approve_loan_application': 'Approve Loan Application',
    'approved_amount': 'Approved Amount',
    'maximum_amount': 'Maximum Amount',
    'interest_rate': 'Interest Rate',
    'duration_months': 'Duration (Months)',
    'approval_notes': 'Approval Notes',
    'approval_notes_placeholder': 'Approval notes (optional)...',
    'loan_summary_calculated': 'Loan Summary (Calculated)',
    'monthly_payment': 'Monthly Payment',
    'total_interest': 'Total Interest',
    'total_repayable': 'Total Repayable',
    'cancel': 'Cancel',
    'approve_loan': 'Approve Loan',
    'reject_loan_application': 'Reject Loan Application',
    'reject_action_warning': 'This action rejects the loan application. The member may submit a new application.',
    'rejection_reason': 'Rejection Reason',
    'rejection_reason_placeholder': 'Explain the reason for rejection...',
    'reject_loan': 'Reject Loan',
    'view_documents': 'View Documents',
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