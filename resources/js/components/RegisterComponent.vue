<template>
  <div class="register-container">
    <div class="register-box">
      <h2>Inscription</h2>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="name">Nom</label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            required
            placeholder="Votre nom complet"
          />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            required
            placeholder="Votre email"
          />
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            required
            placeholder="Au moins 8 caractères"
          />
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirmer le mot de passe</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            id="password_confirmation"
            required
            placeholder="Confirmer votre mot de passe"
          />
        </div>
        <div class="form-group">
          <label for="role">Rôle</label>
          <select v-model="form.role" id="role" required>
            <option value="">-- Sélectionner un rôle --</option>
            <option value="patient">Patient</option>
            <option value="doctor">Médecin</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
        <button type="submit" :disabled="loading">
          {{ loading ? 'Inscription...' : 'S\'inscrire' }}
        </button>
        <p v-if="error" class="error">{{ error }}</p>
      </form>
      <p class="login-link">
        Déjà inscrit?
        <a href="/login">Se connecter</a>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterComponent',
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: ''
      },
      loading: false,
      error: null
    };
  },
  methods: {
    async handleRegister() {
      if (this.form.password !== this.form.password_confirmation) {
        this.error = 'Les mots de passe ne correspondent pas';
        return;
      }

      this.loading = true;
      this.error = null;
      try {
        const response = await fetch('/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
          },
          body: JSON.stringify(this.form)
        });

        if (!response.ok) {
          const data = await response.json();
          throw new Error(data.message || 'Erreur lors de l\'inscription');
        }

        const data = await response.json();
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        
        window.location.href = '/';
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
}

.register-box {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
}

h2 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
  color: #555;
  font-weight: 500;
}

input,
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

input:focus,
select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
}

button {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s;
}

button:hover:not(:disabled) {
  transform: translateY(-2px);
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error {
  color: #e74c3c;
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
}

.login-link {
  text-align: center;
  margin-top: 20px;
  color: #666;
}

.login-link a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>
