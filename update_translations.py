import json

# Update en.json
with open('lang/en.json', 'r', encoding='utf-8') as f:
    en_data = json.load(f)

# Add welcome page translation keys
en_new_keys = {
    # Hero section
    'trusted_by_members': 'Trusted by {{count}}+ Members',
    'empowering_financial_growth': 'Empowering <span class="shine-text">Financial Growth</span> Through Digital Innovation',
    'track_contributions': 'Track contributions, manage loans, and coordinate meetings — all in one secure, intuitive platform designed for modern investment clubs.',
    'see_how_it_works': 'See How It Works',
    'bank_level_security': 'Bank-Level Security',
    '256_bit_encryption': '256-bit encryption',
    'real_time_updates': 'Real-time Updates',
    'instant_notifications': 'Instant notifications',

    # Features section
    'features': 'Features',
    'powerful_features': 'Powerful Features for Modern Investment Clubs',
    'everything_you_need': 'Everything you need to manage your investment club efficiently and transparently',
    'smart_contributions': 'Smart Contributions',
    'track_monthly_savings': 'Track your monthly savings with full transparency. Automated reminders and detailed history at your fingertips.',
    'automated_tracking': 'Automated tracking',
    'real_time_updates_short': 'Real-time updates',
    'mobile_notifications': 'Mobile notifications',
    'explore_feature': 'Explore Feature',
    'loan_management': 'Loan Management',
    'apply_internal_loans': 'Apply for internal loans and monitor repayment status easily. Real-time updates and flexible payment options.',
    'quick_applications': 'Quick applications',
    'automated_approvals': 'Automated approvals',
    'flexible_repayment': 'Flexible repayment',
    'meeting_coordination': 'Meeting Coordination',
    'stay_updated_meetings': 'Stay updated on upcoming meetings and access archived minutes. Never miss an important decision.',
    'calendar_integration': 'Calendar integration',
    'minutes_archive': 'Minutes archive',
    'attendance_tracking': 'Attendance tracking',

    # Services section
    'our_services': 'Our Services',
    'comprehensive_financial': 'Comprehensive Financial Solutions',
    'end_to_end_solutions': 'We provide end-to-end solutions for all your investment club needs',
    'portfolio_management': 'Portfolio Management',
    'track_optimize_portfolio': 'Track and optimize your investment portfolio',
    'financial_reporting': 'Financial Reporting',
    'detailed_reports_analytics': 'Detailed reports and analytics',
    'secure_transactions': 'Secure Transactions',
    'bank_level_security_desc': 'Bank-level security for all transactions',
    'mobile_access': 'Mobile Access',
    'access_anywhere': 'Access your account anywhere, anytime',
    'support_24_7': '24/7 Support',
    'round_clock_support': 'Round-the-clock customer support',
    'auto_sync': 'Auto-sync',
    'auto_data_sync': 'Automatic data synchronization',
    'learn_more': 'Learn More',

    # Team section
    'our_team': 'Our Team',
    'meet_leadership': 'Meet Our Leadership Team',
    'dedicated_professionals': 'Dedicated professionals guiding our investment club to success',
    'jane_namutebi': 'Jane Namutebi',
    'chairperson': 'Chairperson',
    'jane_desc': 'Oversees club governance and strategic direction with over 10 years of investment experience.',
    'david_kato': 'David Kato',
    'treasurer': 'Treasurer',
    'david_desc': 'Manages contributions, loans, and financial reporting with precision and transparency.',
    'sarah_nansubuga': 'Sarah Nansubuga',
    'secretary': 'Secretary',
    'sarah_desc': 'Coordinates meetings and maintains member records with exceptional organizational skills.',

    # Testimonials
    'testimonials': 'Testimonials',
    'what_members_say': 'What Our Members Say',
    'join_satisfied_members': 'Join thousands of satisfied members who trust our platform',
    'john_musoke': 'John Musoke',
    'member_since_2023': 'Member since 2023',
    'john_testimonial': 'This platform transformed how our investment club operates. The transparency and ease of use are incredible!',
    'mary_nakato': 'Mary Nakato',
    'business_owner': 'Business Owner',
    'mary_testimonial': 'The loan management system saved us countless hours. Highly recommended for any investment group!',
    'robert_ssemwanga': 'Robert Ssemwanga',
    'club_treasurer': 'Club Treasurer',
    'robert_testimonial': 'Best decision we made for our club. The reporting features alone are worth it!',

    # FAQ
    'faq': 'FAQ',
    'frequently_asked': 'Frequently Asked Questions',
    'find_answers': 'Find answers to common questions about our investment club and platform.',
    'ask_question': 'Ask a Question',
    'who_can_join': 'Who can join the club?',
    'who_can_join_a': 'Any resident of Kampala with a commitment to monthly contributions and ethical investment practices. We welcome individuals who are serious about financial growth and community development.',
    'how_loans_approved': 'How are loans approved?',
    'how_loans_approved_a': 'Loan applications are reviewed by the executive committee and approved based on contribution history, purpose, and repayment capacity. Decisions are typically made within 3-5 business days.',
    'contribution_requirements': 'What are the contribution requirements?',
    'contribution_requirements_a': 'Members are required to make monthly contributions as agreed upon during registration. Minimum contribution is UGX 50,000 per month. Contributions can be made through mobile money or bank deposit.',
    'is_data_secure': 'Is my data secure?',
    'is_data_secure_a': 'Yes! We use bank-level security encryption to protect your data. All transactions are logged and monitored. We never share your personal information with third parties without your consent.',

    # CTA
    'ready_to_transform': 'Ready to Transform Your Investment Club?',
    'join_hundreds': 'Join hundreds of successful members who have already transformed their financial journey.',
    'existing_member_login': 'Existing Member Login',
    'request_new_sacco': 'Request New SACCO',
    'contact_info': '<i class="fas fa-phone me-2"></i>+256 700 123456 | <i class="fas fa-envelope ms-3 me-2"></i>info@capitalclub.org | <i class="fas fa-map-marker-alt ms-3 me-2"></i>Kampala, Uganda',

    # Footer
    'empowering_members': 'Empowering members through transparent digital finance solutions and community-driven investments.',
    'quick_links': 'Quick Links',
    'member_area_footer': 'Member Area',
    'member_login': 'Member Login',
    'dashboard': 'Dashboard',
    'help_center': 'Help Center',
    'contact_support': 'Contact Support',
    'contact_info_footer': 'Contact Info',
    'address_footer': 'Plot 123, Kampala Road<br>Kampala, Uganda',
    'phone_footer': '+256 700 123456',
    'email_footer': 'info@capitalclub.org',
    'copyright': '&copy; 2025 Capital Investment Club. All rights reserved.',
    'privacy_policy': 'Privacy Policy',
    'terms_of_service': 'Terms of Service',
    'cookie_policy': 'Cookie Policy',

    # Stats
    'active_members': 'Active Members',
    'million_contributions': 'Million in Contributions',
    'loans_approved': 'Loans Approved',
    'member_satisfaction': 'Member Satisfaction',
}

en_data.update(en_new_keys)

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en_data, f, indent=4, ensure_ascii=False)

print('en.json updated with welcome page keys')

# Update fr.json
with open('lang/fr.json', 'r', encoding='utf-8') as f:
    fr_data = json.load(f)

# Add welcome page translation keys in French
fr_new_keys = {
    # Hero section
    'trusted_by_members': 'Plus de {{count}} membres nous font confiance',
    'empowering_financial_growth': 'Renforcer la <span class=\"shine-text\">Croissance Financière</span> par l\'Innovation Numérique',
    'track_contributions': 'Suivez vos cotisations, gérez vos prêts et organisez vos réunions — tout en une seule plateforme sécurisée et intuitive conçue pour les clubs d\'investissement modernes.',
    'see_how_it_works': 'Voir comment ça marche',
    'bank_level_security': 'Sécurité de Niveau Bancaire',
    '256_bit_encryption': 'Chiffrement 256 bits',
    'real_time_updates': 'Mises à jour en temps réel',
    'instant_notifications': 'Notifications instantanées',

    # Features section
    'features': 'Fonctionnalités',
    'powerful_features': 'Fonctionnalités Puissantes pour les Clubs d\'Investissement Modernes',
    'everything_you_need': 'Tout ce dont vous avez besoin pour gérer votre club d\'investissement efficacement et en toute transparence',
    'smart_contributions': 'Cotisations Intelligentes',
    'track_monthly_savings': 'Suivez vos épargnes mensuelles en toute transparence. Rappels automatisés et historique détaillé à portée de main.',
    'automated_tracking': 'Suivi automatisé',
    'real_time_updates_short': 'Mises à jour en temps réel',
    'mobile_notifications': 'Notifications mobiles',
    'explore_feature': 'Explorer la fonctionnalité',
    'loan_management': 'Gestion des Prêts',
    'apply_internal_loans': 'Demandez des prêts internes et suivez l\'état des remboursements facilement. Mises à jour en temps réel et options de paiement flexibles.',
    'quick_applications': 'Demandes rapides',
    'automated_approvals': 'Approbations automatisées',
    'flexible_repayment': 'Remboursement flexible',
    'meeting_coordination': 'Coordination des Réunions',
    'stay_updated_meetings': 'Restez informé des réunions à venir et accédez aux procès-verbaux archivés. Ne manquez jamais une décision importante.',
    'calendar_integration': 'Intégration calendrier',
    'minutes_archive': 'Archives des procès-verbaux',
    'attendance_tracking': 'Suivi des présences',

    # Services section
    'our_services': 'Nos Services',
    'comprehensive_financial': 'Solutions Financières Complètes',
    'end_to_end_solutions': 'Nous fournissons des solutions de bout en bout pour tous les besoins de votre club d\'investissement',
    'portfolio_management': 'Gestion de Portefeuille',
    'track_optimize_portfolio': 'Suivez et optimisez votre portefeuille d\'investissement',
    'financial_reporting': 'Rapports Financiers',
    'detailed_reports_analytics': 'Rapports détaillés et analyses',
    'secure_transactions': 'Transactions Sécurisées',
    'bank_level_security_desc': 'Sécurité de niveau bancaire pour toutes les transactions',
    'mobile_access': 'Accès Mobile',
    'access_anywhere': 'Accédez à votre compte n\'importe où, n\'importe quand',
    'support_24_7': 'Support 24/7',
    'round_clock_support': 'Support client 24h/24 et 7j/7',
    'auto_sync': 'Auto-sync',
    'auto_data_sync': 'Synchronisation automatique des données',
    'learn_more': 'En savoir plus',

    # Team section
    'our_team': 'Notre Équipe',
    'meet_leadership': 'Rencontrez Notre Équipe de Direction',
    'dedicated_professionals': 'Des professionnels dédiés guidant notre club d\'investissement vers le succès',
    'jane_namutebi': 'Jane Namutebi',
    'chairperson': 'Présidente',
    'jane_desc': 'Supervise la gouvernance du club et la direction stratégique avec plus de 10 ans d\'expérience en investissement.',
    'david_kato': 'David Kato',
    'treasurer': 'Trésorier',
    'david_desc': 'Gère les cotisations, les prêts et les rapports financiers avec précision et transparence.',
    'sarah_nansubuga': 'Sarah Nansubuga',
    'secretary': 'Secrétaire',
    'sarah_desc': 'Coordonne les réunions et maintient les registres des membres avec des compétences organisationnelles exceptionnelles.',

    # Testimonials
    'testimonials': 'Témoignages',
    'what_members_say': 'Ce que Disent Nos Membres',
    'join_satisfied_members': 'Rejoignez des milliers de membres satisfaits qui font confiance à notre plateforme',
    'john_musoke': 'John Musoke',
    'member_since_2023': 'Membre depuis 2023',
    'john_testimonial': 'Cette plateforme a transformé la façon dont notre club d\'investissement fonctionne. La transparence et la facilité d\'utilisation sont incroyables !',
    'mary_nakato': 'Mary Nakato',
    'business_owner': 'Chef d\'entreprise',
    'mary_testimonial': 'Le système de gestion des prêts nous a fait gagner d\'innombrables heures. Hautement recommandé pour tout groupe d\'investissement !',
    'robert_ssemwanga': 'Robert Ssemwanga',
    'club_treasurer': 'Trésorier du Club',
    'robert_testimonial': 'Meilleure décision que nous ayons prise pour notre club. Les fonctionnalités de rapportage valent à elles seules le coup !',

    # FAQ
    'faq': 'FAQ',
    'frequently_asked': 'Questions Fréquemment Posées',
    'find_answers': 'Trouvez les réponses aux questions courantes sur notre club d\'investissement et notre plateforme.',
    'ask_question': 'Poser une question',
    'who_can_join': 'Qui peut rejoindre le club ?',
    'who_can_join_a': 'Tout résident de Kampala avec un engagement pour les cotisations mensuelles et des pratiques d\'investissement éthiques. Nous accueillons les personnes sérieuses sur la croissance financière et le développement communautaire.',
    'how_loans_approved': 'Comment les prêts sont-ils approuvés ?',
    'how_loans_approved_a': 'Les demandes de prêts sont examinées par le comité exécutif et approuvées sur la base de l\'historique des cotisations, de l\'objet et de la capacité de remboursement. Les décisions sont généralement prises sous 3-5 jours ouvrables.',
    'contribution_requirements': 'Quelles sont les exigences de cotisation ?',
    'contribution_requirements_a': 'Les membres doivent effectuer des cotisations mensuelles selon les termes convenus lors de l\'inscription. La cotisation minimale est de 50 000 UGX par mois. Les cotisations peuvent être effectuées par mobile money ou virement bancaire.',
    'is_data_secure': 'Mes données sont-elles sécurisées ?',
    'is_data_secure_a': 'Oui ! Nous utilisons un chiffrement de niveau bancaire pour protéger vos données. Toutes les transactions sont journalisées et surveillées. Nous ne partageons jamais vos informations personnelles avec des tiers sans votre consentement.',

    # CTA
    'ready_to_transform': 'Prêt à Transformer Votre Club d\'Investissement ?',
    'join_hundreds': 'Rejoignez des centaines de membres qui ont déjà transformé leur parcours financier.',
    'existing_member_login': 'Connexion Membre Existant',
    'request_new_sacco': 'Demander un Nouveau SACCO',
    'contact_info': '<i class=\"fas fa-phone me-2\"></i>+256 700 123456 | <i class=\"fas fa-envelope ms-3 me-2\"></i>info@capitalclub.org | <i class=\"fas fa-map-marker-alt ms-3 me-2\"></i>Kampala, Ouganda',

    # Footer
    'empowering_members': 'Donner du pouvoir aux membres grâce à des solutions financières numériques transparentes et des investissements pilotés par la communauté.',
    'quick_links': 'Liens Rapides',
    'member_area_footer': 'Espace Membre',
    'member_login': 'Connexion Membre',
    'dashboard': 'Tableau de Bord',
    'help_center': 'Centre d\'Aide',
    'contact_support': 'Contacter le Support',
    'contact_info_footer': 'Infos de Contact',
    'address_footer': 'Parcelle 123, Route de Kampala<br>Kampala, Ouganda',
    'phone_footer': '+256 700 123456',
    'email_footer': 'info@capitalclub.org',
    'copyright': '&copy; 2025 Capital Investment Club. Tous droits réservés.',
    'privacy_policy': 'Politique de Confidentialité',
    'terms_of_service': 'Conditions d\'Utilisation',
    'cookie_policy': 'Politique des Cookies',

    # Stats
    'active_members': 'Membres Actifs',
    'million_contributions': 'Millions en Cotisations',
    'loans_approved': 'Prêts Approuvés',
    'member_satisfaction': 'Satisfaction des Membres',
}

fr_data.update(fr_new_keys)

with open('lang/fr.json', 'w', encoding='utf-8') as f:
    json.dump(fr_data, f, indent=4, ensure_ascii=False)

print('fr.json updated with welcome page keys')