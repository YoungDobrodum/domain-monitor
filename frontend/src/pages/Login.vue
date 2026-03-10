<script setup lang="ts">
import {ref} from 'vue';
import api from '../api/axios';
import {useRouter} from 'vue-router';
import locales from '../locales/ru';

const isLogin = ref(true);
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const router = useRouter();

const t = locales.auth;
const err = locales.errors;
const titles = locales.titles;

const handleSubmit = async () => {
  const trimmedEmail = email.value.trim();
  const trimmedName = name.value.trim();

  if (!trimmedEmail || !password.value) {
    alert(err.fillFields);
    return;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(trimmedEmail)) {
    alert(err.email);
    return;
  }

  if (!isLogin.value) {
    if (!trimmedName) {
      alert(err.name);
      return;
    }
    if (password.value.length < 6) {
      alert(err.password);
      return;
    }
    if (password.value !== password_confirmation.value) {
      alert(err.passwordConf);
      return;
    }
  }
  try {
    const endpoint = isLogin.value ? '/login' : '/register';
    const payload = isLogin.value
        ? {email: trimmedEmail, password: password.value}
        : {
          name: trimmedName,
          email: trimmedEmail,
          password: password.value,
          password_confirmation: password_confirmation.value
        };

    const {data} = await api.post(endpoint, payload);

    localStorage.setItem('token', data.token);
    api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;

    router.push('/domains');
  } catch (e: any) {
    const errorMsg = e.response?.data?.errors
        ? Object.values(e.response.data.errors).flat().join('\n')
        : (e.response?.data?.message || err.err);
    alert(errorMsg);
  }
};
</script>

<template>
  <div class="login-page">
    <div class="gradient-bg"></div>
    <div class="glow glow1"></div>
    <div class="glow glow2"></div>
    <div class="login-card">
      <h1 v-text="titles.head"></h1>
      <p class="subtitle" v-text="titles.mid"></p>
      <p class="subtitle" v-text="isLogin ? t.login : t.register"></p>

      <form @submit.prevent="handleSubmit">
        <div
          v-if="!isLogin"
          class="input">
          <input
            v-model="name"
            type="text"
            required
          />
          <span v-text="t.name"></span>
        </div>

        <div class="input">
          <input
            v-model="email"
            type="email"
            required
          />
          <span v-text="t.email"></span>
        </div>

        <div class="input">
          <input
            v-model="password"
            type="password"
            required
          />
          <span v-text="t.password"></span>
        </div>

        <div
            v-if="!isLogin"
            class="input">
          <input
            v-model="password_confirmation"
            type="password"
            required
          />
          <span v-text="t.password"></span>
        </div>

        <button
          type="submit"
          v-text="isLogin ? t.login : t.register"
        />
      </form>
      <p
        class="toggle-text"
        @click="isLogin = !isLogin"
        v-text="isLogin ? t.noAccount : t.hasAccount">
      </p>
    </div>
  </div>
</template>

<style scoped>

.login-page {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: #050505;
  font-family: Inter, system-ui;
}

.gradient-bg {
  position: absolute;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at 30% 30%, #312e81, transparent 40%),
  radial-gradient(circle at 70% 70%, #1e1b4b, transparent 40%);
  animation: moveGradient 20s infinite alternate;
  filter: blur(120px);
}

@keyframes moveGradient {
  from {
    transform: translate(-10%, -10%)
  }
  to {
    transform: translate(10%, 10%)
  }
}

.glow {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  filter: blur(140px);
  opacity: .4;
}

.glow1 {
  background: #6366f1;
  top: -100px;
  left: -100px;
  animation: float1 18s infinite alternate ease-in-out;
}

.glow2 {
  background: #9333ea;
  bottom: -150px;
  right: -150px;
  animation: float2 22s infinite alternate ease-in-out;
}

@keyframes float1 {
  from {
    transform: translate(0, 0)
  }
  to {
    transform: translate(120px, 80px)
  }
}

@keyframes float2 {
  from {
    transform: translate(0, 0)
  }
  to {
    transform: translate(-120px, -60px)
  }
}

.login-card {
  position: relative;
  z-index: 2;
  width: 420px;
  padding: 50px 45px;
  border-radius: 18px;
  background: rgba(20, 20, 20, .7);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, .05);
  box-shadow: 0 40px 120px rgba(0, 0, 0, .9),
  0 0 40px rgba(99, 102, 241, .15);

  transition: .3s;
}

.login-card:hover {
  transform: translateY(-6px);
}

.login-card h1 {
  color: white;
  font-size: 30px;
  text-align: center;
  margin-bottom: 8px;
}

.subtitle {
  text-align: center;
  color: #888;
  font-size: 14px;
  margin-bottom: 35px;
}

.input {
  position: relative;
  margin-bottom: 24px;
}

.input input {
  width: 100%;
  padding: 14px 12px;
  background: #0b0b0b;
  border: 1px solid #2a2a2a;
  border-radius: 10px;
  color: white;
  font-size: 14px;
  transition: .25s;
}

.input span {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
  font-size: 13px;
  transition: .25s;
  pointer-events: none;
}

.input input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 15px rgba(99, 102, 241, .35);
}

.input input:focus + span,
.input input:valid + span {
  top: -8px;
  font-size: 11px;
  background: #141414;
  padding: 0 6px;
  color: #aaa;
}

button {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  color: white;
  background: linear-gradient(
      135deg,
      #6366f1,
      #4f46e5
  );
  cursor: pointer;
  transition: .25s;
}

button:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px rgba(99, 102, 241, .45);
}

button:active {
  transform: translateY(0);
}

.toggle-text {
  margin-top: 20px;
  cursor: pointer;
  color: #4a90e2;
  font-size: 0.9rem;
  text-decoration: underline;
}

</style>
