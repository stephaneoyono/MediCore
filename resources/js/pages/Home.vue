<template>
  <div class="home-container">
    <nav class="navbar">
      <h1>MediRDV</h1>
      <ul>
        <li><a href="/">Accueil</a></li>
        <li><a href="/login">Connexion</a></li>
        <li><a href="/register">Inscription</a></li>
      </ul>
    </nav>
    <div class="content">
      <h2>Bienvenue sur MediRDV</h2>
      <p>Système de gestion de rendez-vous médicaux</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home'
};
</script>

<style scoped>
.home-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 40px;
  background: rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar h1 {
  margin: 0;
  font-size: 28px;
}

.navbar ul {
  list-style: none;
  display: flex;
  gap: 30px;
  margin: 0;
  padding: 0;
}

.navbar a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.3s;
}

.navbar a:hover {
  opacity: 0.8;
}

.content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 80px);
  text-align: center;
}

.content h2 {
  font-size: 48px;
  margin: 0 0 20px 0;
}

.content p {
  font-size: 24px;
  opacity: 0.9;
}
</style>