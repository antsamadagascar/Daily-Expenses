INSERT INTO categories (name, color, icon, is_default, user_id, created_at, updated_at) VALUES
('Alimentation', '#10B981', 'shopping-cart', true, NULL, NOW(), NOW()),
('Transport', '#3B82F6', 'car', true, NULL, NOW(), NOW()),
('Logement', '#8B5CF6', 'home', true, NULL, NOW(), NOW()),
('Santé', '#EF4444', 'heart', true, NULL, NOW(), NOW()),
('Loisirs', '#F59E0B', 'play', true, NULL, NOW(), NOW()),
('Vêtements', '#EC4899', 'shirt', true, NULL, NOW(), NOW()),
('Éducation', '#06B6D4', 'book', true, NULL, NOW(), NOW()),
('Autres', '#6B7280', 'more-horizontal', true, NULL, NOW(), NOW());

-- Données de test pour la table expenses (2024-2025)
-- Dépenses de 2024
INSERT INTO expenses (user_id, category_id, amount, description, expense_date, notes, created_at, updated_at) VALUES

-- Janvier 2024
(1, 1, 85.50, 'Courses alimentaires Carrefour', '2024-01-03', 'Achats de la semaine', '2024-01-03 10:30:00', '2024-01-03 10:30:00'),
(1, 2, 45.00, 'Plein essence', '2024-01-05', 'Station Total', '2024-01-05 08:15:00', '2024-01-05 08:15:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-01-01', 'Loyer janvier 2024', '2024-01-01 00:00:00', '2024-01-01 00:00:00'),
(1, 4, 25.00, 'Pharmacie - Médicaments', '2024-01-08', 'Doliprane et vitamines', '2024-01-08 14:20:00', '2024-01-08 14:20:00'),
(1, 5, 12.50, 'Cinéma', '2024-01-12', 'Film du vendredi soir', '2024-01-12 20:00:00', '2024-01-12 20:00:00'),

-- Février 2024
(1, 1, 92.30, 'Courses alimentaires Leclerc', '2024-02-01', 'Achats bi-hebdomadaires', '2024-02-01 11:45:00', '2024-02-01 11:45:00'),
(1, 2, 38.70, 'Ticket de métro mensuel', '2024-02-01', 'Abonnement transport', '2024-02-01 07:30:00', '2024-02-01 07:30:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-02-01', 'Loyer février 2024', '2024-02-01 00:00:00', '2024-02-01 00:00:00'),
(1, 6, 79.99, 'Veste hiver', '2024-02-14', 'Soldes chez Zara', '2024-02-14 15:30:00', '2024-02-14 15:30:00'),
(1, 5, 35.00, 'Restaurant', '2024-02-14', 'Dîner Saint-Valentin', '2024-02-14 19:45:00', '2024-02-14 19:45:00'),

-- Mars 2024
(1, 1, 76.80, 'Courses alimentaires', '2024-03-05', 'Marché local', '2024-03-05 09:00:00', '2024-03-05 09:00:00'),
(1, 2, 52.00, 'Plein essence', '2024-03-10', 'Station Shell', '2024-03-10 18:20:00', '2024-03-10 18:20:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-03-01', 'Loyer mars 2024', '2024-03-01 00:00:00', '2024-03-01 00:00:00'),
(1, 4, 120.00, 'Consultation médecin', '2024-03-15', 'Visite de contrôle', '2024-03-15 10:00:00', '2024-03-15 10:00:00'),
(1, 7, 450.00, 'Formation en ligne', '2024-03-20', 'Cours de développement web', '2024-03-20 14:00:00', '2024-03-20 14:00:00'),

-- Avril 2024
(1, 1, 88.90, 'Courses alimentaires', '2024-04-02', 'Super U', '2024-04-02 16:30:00', '2024-04-02 16:30:00'),
(1, 2, 43.50, 'Plein essence', '2024-04-07', 'Station Esso', '2024-04-07 12:15:00', '2024-04-07 12:15:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-04-01', 'Loyer avril 2024', '2024-04-01 00:00:00', '2024-04-01 00:00:00'),
(1, 5, 65.00, 'Concert', '2024-04-18', 'Concert jazz au Zenith', '2024-04-18 20:30:00', '2024-04-18 20:30:00'),
(1, 8, 29.99, 'Abonnement Netflix', '2024-04-01', 'Abonnement mensuel', '2024-04-01 08:00:00', '2024-04-01 08:00:00'),

-- Mai 2024
(1, 1, 95.60, 'Courses alimentaires', '2024-05-03', 'Monoprix', '2024-05-03 10:45:00', '2024-05-03 10:45:00'),
(1, 2, 48.20, 'Plein essence', '2024-05-12', 'Station BP', '2024-05-12 17:30:00', '2024-05-12 17:30:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-05-01', 'Loyer mai 2024', '2024-05-01 00:00:00', '2024-05-01 00:00:00'),
(1, 6, 125.00, 'Chaussures de sport', '2024-05-15', 'Nike Air Max', '2024-05-15 14:20:00', '2024-05-15 14:20:00'),
(1, 5, 28.50, 'Théâtre', '2024-05-22', 'Pièce au théâtre municipal', '2024-05-22 19:00:00', '2024-05-22 19:00:00'),

-- Juin 2024
(1, 1, 102.40, 'Courses alimentaires', '2024-06-01', 'Auchan', '2024-06-01 11:00:00', '2024-06-01 11:00:00'),
(1, 2, 55.80, 'Plein essence', '2024-06-08', 'Voyage weekend', '2024-06-08 07:45:00', '2024-06-08 07:45:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-06-01', 'Loyer juin 2024', '2024-06-01 00:00:00', '2024-06-01 00:00:00'),
(1, 5, 180.00, 'Weekend à la plage', '2024-06-15', 'Hôtel + restaurants', '2024-06-15 12:00:00', '2024-06-15 12:00:00'),
(1, 4, 45.00, 'Pharmacie', '2024-06-20', 'Crème solaire et produits été', '2024-06-20 16:30:00', '2024-06-20 16:30:00'),

-- Juillet 2024
(1, 1, 78.90, 'Courses alimentaires', '2024-07-05', 'Petit Casino', '2024-07-05 09:30:00', '2024-07-05 09:30:00'),
(1, 2, 62.00, 'Plein essence', '2024-07-10', 'Départ vacances', '2024-07-10 06:00:00', '2024-07-10 06:00:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-07-01', 'Loyer juillet 2024', '2024-07-01 00:00:00', '2024-07-01 00:00:00'),
(1, 5, 750.00, 'Vacances été', '2024-07-15', 'Séjour une semaine en Espagne', '2024-07-15 10:00:00', '2024-07-15 10:00:00'),
(1, 6, 89.99, 'Maillot de bain', '2024-07-12', 'Achats pour les vacances', '2024-07-12 15:00:00', '2024-07-12 15:00:00'),

-- Août 2024
(1, 1, 69.30, 'Courses alimentaires', '2024-08-02', 'Retour de vacances', '2024-08-02 18:00:00', '2024-08-02 18:00:00'),
(1, 2, 45.60, 'Plein essence', '2024-08-15', 'Station Intermarché', '2024-08-15 13:20:00', '2024-08-15 13:20:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-08-01', 'Loyer août 2024', '2024-08-01 00:00:00', '2024-08-01 00:00:00'),
(1, 8, 156.00, 'Réparation électroménager', '2024-08-20', 'Réparation lave-linge', '2024-08-20 14:30:00', '2024-08-20 14:30:00'),
(1, 5, 42.00, 'Parc d''attractions', '2024-08-25', 'Sortie famille', '2024-08-25 11:00:00', '2024-08-25 11:00:00'),

-- Septembre 2024 (rentrée)
(1, 1, 98.70, 'Courses alimentaires', '2024-09-01', 'Courses de rentrée', '2024-09-01 10:00:00', '2024-09-01 10:00:00'),
(1, 2, 41.20, 'Plein essence', '2024-09-05', 'Reprise du travail', '2024-09-05 08:30:00', '2024-09-05 08:30:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-09-01', 'Loyer septembre 2024', '2024-09-01 00:00:00', '2024-09-01 00:00:00'),
(1, 7, 299.00, 'Livres et fournitures', '2024-09-10', 'Matériel pour formation', '2024-09-10 16:00:00', '2024-09-10 16:00:00'),
(1, 6, 155.00, 'Vêtements automne', '2024-09-15', 'Pull et pantalon', '2024-09-15 14:45:00', '2024-09-15 14:45:00'),

-- Octobre 2024
(1, 1, 87.40, 'Courses alimentaires', '2024-10-03', 'Hyper U', '2024-10-03 11:30:00', '2024-10-03 11:30:00'),
(1, 2, 47.80, 'Plein essence', '2024-10-12', 'Station Leclerc', '2024-10-12 19:00:00', '2024-10-12 19:00:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-10-01', 'Loyer octobre 2024', '2024-10-01 00:00:00', '2024-10-01 00:00:00'),
(1, 4, 85.00, 'Dentiste', '2024-10-18', 'Contrôle annuel', '2024-10-18 09:30:00', '2024-10-18 09:30:00'),
(1, 5, 22.50, 'Cinéma', '2024-10-26', 'Film d''horreur Halloween', '2024-10-26 21:00:00', '2024-10-26 21:00:00'),

-- Novembre 2024
(1, 1, 91.20, 'Courses alimentaires', '2024-11-02', 'Carrefour Market', '2024-11-02 15:20:00', '2024-11-02 15:20:00'),
(1, 2, 44.30, 'Plein essence', '2024-11-08', 'Station Avia', '2024-11-08 12:45:00', '2024-11-08 12:45:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-11-01', 'Loyer novembre 2024', '2024-11-01 00:00:00', '2024-11-01 00:00:00'),
(1, 8, 67.50, 'Électricité', '2024-11-15', 'Facture EDF bimensuelle', '2024-11-15 10:00:00', '2024-11-15 10:00:00'),
(1, 5, 85.00, 'Restaurant gastronomique', '2024-11-20', 'Anniversaire', '2024-11-20 20:00:00', '2024-11-20 20:00:00'),

-- Décembre 2024 (fêtes)
(1, 1, 145.80, 'Courses alimentaires', '2024-12-01', 'Courses de Noël', '2024-12-01 10:30:00', '2024-12-01 10:30:00'),
(1, 2, 38.90, 'Plein essence', '2024-12-10', 'Station Total', '2024-12-10 16:20:00', '2024-12-10 16:20:00'),
(1, 3, 850.00, 'Loyer mensuel', '2024-12-01', 'Loyer décembre 2024', '2024-12-01 00:00:00', '2024-12-01 00:00:00'),
(1, 5, 320.00, 'Cadeaux de Noël', '2024-12-15', 'Achats famille et amis', '2024-12-15 14:00:00', '2024-12-15 14:00:00'),
(1, 5, 125.00, 'Réveillon restaurant', '2024-12-31', 'Dîner du 31 décembre', '2024-12-31 21:00:00', '2024-12-31 21:00:00'),

-- ======= ANNÉE 2025 =======

-- Janvier 2025
(1, 1, 89.60, 'Courses alimentaires', '2025-01-04', 'Première courses de l''année', '2025-01-04 11:00:00', '2025-01-04 11:00:00'),
(1, 2, 48.50, 'Plein essence', '2025-01-06', 'Station Shell', '2025-01-06 09:15:00', '2025-01-06 09:15:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-01-01', 'Loyer janvier 2025 (augmentation)', '2025-01-01 00:00:00', '2025-01-01 00:00:00'),
(1, 4, 95.00, 'Mutuelle santé', '2025-01-01', 'Cotisation annuelle', '2025-01-01 08:00:00', '2025-01-01 08:00:00'),
(1, 7, 599.00, 'Ordinateur portable', '2025-01-15', 'Nouvel ordinateur pour le travail', '2025-01-15 16:30:00', '2025-01-15 16:30:00'),

-- Février 2025
(1, 1, 82.40, 'Courses alimentaires', '2025-02-02', 'Courses Auchan', '2025-02-02 13:45:00', '2025-02-02 13:45:00'),
(1, 2, 42.80, 'Plein essence', '2025-02-08', 'Station BP', '2025-02-08 17:00:00', '2025-02-08 17:00:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-02-01', 'Loyer février 2025', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(1, 5, 45.00, 'Sortie Saint-Valentin', '2025-02-14', 'Dîner romantique', '2025-02-14 19:30:00', '2025-02-14 19:30:00'),
(1, 6, 99.99, 'Manteau hiver', '2025-02-20', 'Soldes d''hiver', '2025-02-20 15:15:00', '2025-02-20 15:15:00'),

-- Mars 2025
(1, 1, 94.70, 'Courses alimentaires', '2025-03-01', 'Super U', '2025-03-01 10:20:00', '2025-03-01 10:20:00'),
(1, 2, 51.20, 'Plein essence', '2025-03-05', 'Station Leclerc', '2025-03-05 14:30:00', '2025-03-05 14:30:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-03-01', 'Loyer mars 2025', '2025-03-01 00:00:00', '2025-03-01 00:00:00'),
(1, 4, 150.00, 'Ophtalmologue', '2025-03-12', 'Contrôle vision + lunettes', '2025-03-12 14:00:00', '2025-03-12 14:00:00'),
(1, 8, 78.90, 'Assurance habitation', '2025-03-15', 'Cotisation trimestrielle', '2025-03-15 09:00:00', '2025-03-15 09:00:00'),

-- Avril 2025
(1, 1, 86.30, 'Courses alimentaires', '2025-04-03', 'Monoprix', '2025-04-03 16:45:00', '2025-04-03 16:45:00'),
(1, 2, 45.60, 'Plein essence', '2025-04-10', 'Station Esso', '2025-04-10 11:30:00', '2025-04-10 11:30:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-04-01', 'Loyer avril 2025', '2025-04-01 00:00:00', '2025-04-01 00:00:00'),
(1, 5, 75.00, 'Festival de musique', '2025-04-18', 'Entrée festival local', '2025-04-18 18:00:00', '2025-04-18 18:00:00'),
(1, 7, 89.00, 'Livres spécialisés', '2025-04-25', 'Formation continue', '2025-04-25 12:00:00', '2025-04-25 12:00:00'),

-- Mai 2025
(1, 1, 97.80, 'Courses alimentaires', '2025-05-02', 'Carrefour', '2025-05-02 09:45:00', '2025-05-02 09:45:00'),
(1, 2, 49.40, 'Plein essence', '2025-05-08', 'Station Total', '2025-05-08 18:20:00', '2025-05-08 18:20:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-05-01', 'Loyer mai 2025', '2025-05-01 00:00:00', '2025-05-01 00:00:00'),
(1, 6, 135.00, 'Vêtements printemps', '2025-05-12', 'Renouvellement garde-robe', '2025-05-12 14:15:00', '2025-05-12 14:15:00'),
(1, 5, 65.00, 'Match de football', '2025-05-20', 'Billet stade', '2025-05-20 20:30:00', '2025-05-20 20:30:00'),

-- Juin 2025
(1, 1, 101.50, 'Courses alimentaires', '2025-06-01', 'Auchan', '2025-06-01 11:15:00', '2025-06-01 11:15:00'),
(1, 2, 53.70, 'Plein essence', '2025-06-07', 'Préparation weekend', '2025-06-07 16:00:00', '2025-06-07 16:00:00'),
(1, 3, 875.00, 'Loyer mensuel', '2025-06-01', 'Loyer juin 2025', '2025-06-01 00:00:00', '2025-06-01 00:00:00'),
(1, 5, 220.00, 'Weekend romantique', '2025-06-14', 'Hôtel + dîners', '2025-06-14 15:30:00', '2025-06-14 15:30:00'),
(1, 8, 125.00, 'Réparation voiture', '2025-06-20', 'Vidange + contrôles', '2025-06-20 10:00:00', '2025-06-20 10:00:00');

-- Ajout de quelques dépenses supplémentaires 
INSERT INTO expenses (user_id, category_id, amount, description, expense_date, notes, created_at, updated_at) VALUES
-- Dépenses récurrentes supplémentaires
(1, 8, 29.99, 'Abonnement Spotify', '2024-01-01', 'Abonnement musique mensuel', '2024-01-01 08:00:00', '2024-01-01 08:00:00'),
(1, 8, 29.99, 'Abonnement Spotify', '2024-02-01', 'Abonnement musique mensuel', '2024-02-01 08:00:00', '2024-02-01 08:00:00'),
(1, 8, 29.99, 'Abonnement Spotify', '2024-03-01', 'Abonnement musique mensuel', '2024-03-01 08:00:00', '2024-03-01 08:00:00'),
(1, 8, 29.99, 'Abonnement Spotify', '2024-04-01', 'Abonnement musique mensuel', '2024-04-01 08:00:00', '2024-04-01 08:00:00'),
(1, 8, 29.99, 'Abonnement Spotify', '2024-05-01', 'Abonnement musique mensuel', '2024-05-01 08:00:00', '2024-05-01 08:00:00'),
(1, 8, 29.99, 'Abonnement Spotify', '2024-06-01', 'Abonnement musique mensuel', '2024-06-01 08:00:00', '2024-06-01 08:00:00'),

-- Dépenses exceptionnelles
(1, 4, 350.00, 'Urgence dentaire', '2024-05-20', 'Soins dentaires non programmés', '2024-05-20 15:00:00', '2024-05-20 15:00:00'),
(1, 8, 450.00, 'Réparation électroménager', '2024-07-08', 'Réparation réfrigérateur', '2024-07-08 09:30:00', '2024-07-08 09:30:00'),
(1, 2, 120.00, 'Amende stationnement', '2024-09-12', 'Contravention parking', '2024-09-12 00:00:00', '2024-09-12 00:00:00'),
(1, 5, 500.00, 'Cadeau anniversaire ami', '2024-11-05', 'Cadeau spécial 30 ans', '2024-11-05 17:20:00', '2024-11-05 17:20:00');