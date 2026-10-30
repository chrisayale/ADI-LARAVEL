import json

# Read existing files
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr = json.load(f)

with open('lang/en.json', 'r', encoding='utf-8') as f:
    en = json.load(f)

# Add new translation keys for disbursed loans view
new_fr = {
    'disbursed_loans': 'Prêts décaissés',
    'total_disbursed': 'Total décaissé',
    'total_amount': 'Montant total',
    'total_repaid': 'Total remboursé',
    'outstanding': 'En cours',
    'filter_disbursed_loans': 'Filtrer les prêts décaissés',
    'search_member': 'Rechercher un membre',
    'search_member_placeholder': 'Nom ou numéro de membre...',
    'repayment_status': 'Statut de remboursement',
    'disbursed_date': 'Date de décaissement',
    'all': 'Tous',
    'on_track': 'En bonne voie',
    'overdue': 'En retard',
    'completed': 'Terminé',
    'disbursed_loans_list': 'Liste des prêts décaissés',
    'application_no': 'N° demande',
    'member': 'Membre',
    'amount_disbursed': 'Montant décaissé',
    'interest_rate': 'Taux d\'intérêt',
    'duration': 'Durée',
    'months': 'mois',
    'monthly_payment': 'Paiement mensuel',
    'total_repayable': 'Total remboursable',
    'disbursed_date': 'Date de décaissement',
    'amount_repaid': 'Montant remboursé',
    'balance': 'Solde',
    'progress': 'Progression',
    'actions': 'Actions',
    'view_details': 'Voir les détails',
    'mark_as_completed': 'Marquer comme terminé',
    'confirm_mark_completed': 'Marquer ce prêt comme terminé ?',
    'no_disbursed_loans_found': 'Aucun prêt décaissé trouvé',
    'back_to_dashboard': 'Retour au tableau de bord',
}

new_en = {
    'disbursed_loans': 'Disbursed Loans',
    'total_disbursed': 'Total Disbursed',
    'total_amount': 'Total Amount',
    'total_repaid': 'Total Repaid',
    'outstanding': 'Outstanding',
    'filter_disbursed_loans': 'Filter Disbursed Loans',
    'search_member': 'Search Member',
    'search_member_placeholder': 'Name or Membership Number...',
    'repayment_status': 'Repayment Status',
    'disbursed_date': 'Disbursed Date',
    'all': 'All',
    'on_track': 'On Track',
    'overdue': 'Overdue',
    'completed': 'Completed',
    'disbursed_loans_list': 'Disbursed Loans List',
    'application_no': 'Application No.',
    'member': 'Member',
    'amount_disbursed': 'Amount Disbursed',
    'interest_rate': 'Interest Rate',
    'duration': 'Duration',
    'months': 'months',
    'monthly_payment': 'Monthly Payment',
    'total_repayable': 'Total Repayable',
    'disbursed_date': 'Disbursed Date',
    'amount_repaid': 'Amount Repaid',
    'balance': 'Balance',
    'progress': 'Progress',
    'actions': 'Actions',
    'view_details': 'View Details',
    'mark_as_completed': 'Mark as Completed',
    'confirm_mark_completed': 'Mark this loan as completed?',
    'no_disbursed_loans_found': 'No disbursed loans found',
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