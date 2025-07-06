# 🚀 Guide d'installation – Daily Expenses

Suivez les étapes ci-dessous pour installer et exécuter le projet localement.

---

## 📥 1. Cloner le dépôt

```bash
git clone -b main https://github.com/antsamadagascar/Daily-Expenses.git
cd Daily-Expenses/
```

---

## 👤 2. Créer un utilisateur via Tinker

```bash
php artisan tinker
```

Puis :

```php
use App\Models\User;

User::create([
    'name' => 'user',
    'username' => 'Administrator',
    'email' => 'youruser@gmail.com',
    'password' => bcrypt('your_mdp'),
]);
```

---

## 🧱 3. Générer les fichiers de migration (si nécessaire)

Si la migration de la table `users` n'existe pas encore, créez-la avec :

```bash
php artisan make:migration create_users_table
```

## ⚙️ 4. Exécuter les migrations et lancer le serveur

```bash
php artisan migrate
php artisan serve



## ✅ L'application est maintenant prête à l'emploi !

Accédez à :  
http://127.0.0.1:8000/

Connectez-vous avec :

- Email : youruser@gmail.com  
- Mot de passe : your_mdp