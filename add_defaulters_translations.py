import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for defaulters view
new_fr = {
    'loan_defaulters': 'Défauts de paiement',
    'export_list': 'Exporter la liste',
    'total_defaulters': 'Total des défauts',
    'total_overdue_amount': 'Montant total en retard',
    'average_overdue_days': 'Jours de retard moyens',
    'highest_risk': 'Risque le plus élevé',
    'filter_defaulters': 'Filtrer les défauts',
    'search_member': 'Rechercher un membre',
    'search_member_placeholder': 'Nom ou N° adhésion...',
    'follow_up_status': 'Statut de suivi',
    'assigned_to': 'Assigné à',
    'all_status': 'Tous les statuts',
    'new': 'Nouveau',
    'follow_up_1': '1er suivi',
    'follow_up_2': '2e suivi',
    'final_notice': 'Dernier avis',
    'legal_action': 'Action légale',
    'all_treasurers': 'Tous les trésoriers',
    'defaulters_list': 'Liste des défauts',
    'member': 'Membre',
    'loan_details': 'Détails du prêt',
    'overdue_amount': 'Montant en retard',
    'days_overdue': 'Jours de retard',
    'last_payment': 'Dernier paiement',
    'follow_up_status': 'Statut de suivi',
    'last_follow_up': 'Dernier suivi',
    'actions': 'Actions',
    'amount': 'Montant',
    'monthly': 'Mensuel',
    'days': 'jours',
    'never': 'Jamais',
    'unassigned': 'Non assigné',
    'next': 'Prochain',
    'not_followed_up': 'Pas encore suivi',
    'follow_up': 'Suivi',
    'assign': 'Assigner',
    'view_loan': 'Voir le prêt',
    'record_follow_up': 'Enregistrer un suivi',
    'follow_up_notes': 'Notes de suivi',
    'follow_up_notes_placeholder': 'Entrez les détails du suivi...',
    'next_follow_up_date': 'Date du prochain suivi',
    'save_follow_up': 'Enregistrer le suivi',
    'assign_defaulter_follow_up': 'Assigner un défaut pour suivi',
    'select_treasurer': 'Sélectionner un trésorier',
    'choose_treasurer': 'Choisir un trésorier...',
    'cancel': 'Annuler',
    'assign': 'Assigner',
    'no_defaulters_found': 'Aucun défaut trouvé',
    'back_to_dashboard': 'Retour au tableau de bord',
}

new_en = {
    'loan_defaulters': 'Loan Defaulters',
    'export_list': 'Export List',
    'total_defaulters': 'Total Defaulters',
    'total_overdue_amount': 'Total Overdue Amount',
    'average_overdue_days': 'Average Overdue Days',
    'highest_risk': 'Highest Risk',
    'filter_defaulters': 'Filter Defaulters',
    'search_member': 'Search Member',
    'search_member_placeholder': 'Name or Membership No...',
    'follow_up_status': 'Follow-up Status',
    'assigned_to': 'Assigned To',
    'all_status': 'All Status',
    'new': 'New',
    'follow_up_1': '1st Follow-up',
    'follow_up_2': '2nd Follow-up',
    'final_notice': 'Final Notice',
    'legal_action': 'Legal Action',
    'all_treasurers': 'All Treasurers',
    'defaulters_list': 'Defaulters List',
    'member': 'Member',
    'loan_details': 'Loan Details',
    'overdue_amount': 'Overdue Amount',
    'days_overdue': 'Days Overdue',
    'last_payment': 'Last Payment',
    'follow_up_status': 'Follow-up Status',
    'last_follow_up': 'Last Follow-up',
    'actions': 'Actions',
    'amount': 'Amount',
    'monthly': 'Monthly',
    'days': 'days',
    'never': 'Never',
    'unassigned': 'Unassigned',
    'next': 'Next',
    'not_followed_up': 'Not Followed Up',
    'follow_up': 'Follow Up',
    'assign': 'Assign',
    'view_loan': 'View Loan',
    'record_follow_up': 'Record Follow-up',
    'follow_up_notes': 'Follow-up Notes',
    'follow_up_notes_placeholder': 'Enter details of the follow-up...',
    'next_follow_up_date': 'Next Follow-up Date',
    'save_follow_up': 'Save Follow-up',
    'assign_defaulter_follow_up': 'Assign Defaulter for Follow-up',
    'select_treasurer': 'Select Treasurer',
    'choose_treasurer': 'Choose Treasurer...',
    'cancel': 'Cancel',
    'assign': 'Assign',
    'no_defaulters_found': 'No defaulters found',
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