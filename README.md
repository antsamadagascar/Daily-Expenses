# 📊 Daily Expenses

Application web de gestion budgétaire personnelle permettant de suivre, analyser et visualiser ses dépenses de manière efficace.

---

## 🔐 Authentification

- Connexion
- Déconnexion

---

## 💰 Gestion des Budgets

- Ajout multiple de budgets
- Filtrage des budgets par :
  - Nom
  - Statut
- Affichage :
  - Montant total
  - Reste budgétaire (en fonction du filtre et du jour actuel)
- Réinitialisation des données de la table budgets

---

## 💸 Gestion des Dépenses

- Ajout multiple de dépenses
- Filtrage des dépenses par :
  - Date de début et de fin
  - Mois
  - Année
  - Catégories
  - Mots-clés
- Affichage des dépenses avec :
  - CRUD complet
  - Pagination
- Réinitialisation des filtres pour afficher les données actuelles
- Réinitialisation des données de la table dépenses
- Répartition des dépenses par catégories
- Export des dépenses en PDF pour un mois donné

---

## 📈 Tableau de Bord

- **Filtrage par année et mois**
- **Statistiques globales :**
  - Total des dépenses
  - Dépenses du mois
  - Nombre de dépenses
  - Moyenne journalière
  - Budget restant
- **Comparaison annuelle :**
  - Dépenses de l'année actuelle vs année précédente
  - Différences en valeur et en pourcentage
- **Visualisations graphiques :**
  - Évolution mensuelle des dépenses (sur 12 mois)
  - Répartition par catégories
  - Tendances hebdomadaires (7 derniers jours)
- **Fonctionnalités supplémentaires :**
  - Filtrage des dépenses récentes
  - Affichage d’un calendrier de suivi des dépenses

---

## 🛠️ Technologies

Laravel, Livewire, TailwindCSS, Chart.js, Bootstrap (minimal), Blade Components

---

## 📁 Structure recommandée

- `app/Livewire` – Composants Livewire du tableau de bord
- `resources/views` – Pages Blade (formulaires, tableaux, dashboards)
- `public/pdf` – Exports PDF
