<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api/axios';
import locale from '../locales/ru.ts'
import { ArrowLeft, RefreshCcw, CheckCircle, XCircle } from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();
const checks = ref([]);
const domainId = route.params.id;
const l = locale;


const fetchHistory = async () => {
  try {
    const { data } = await api.get(`/domains/${domainId}/checks`);
    checks.value = data;
  } catch (e) {
    console.error(l.errors.errLoad);
  }
};

onMounted(fetchHistory);

</script>

<template>
  <div class="dashboard">
    <div class="container">
      <header class="header">
        <div class="brand">
          <button @click="router.push('/domains')" class="back-btn">
            <ArrowLeft size="20" />
          </button>
          <h1>
            <span v-text="l.checks.title"></span>
          </h1>
        </div>
        <button @click="fetchHistory" class="refresh-btn">
          <RefreshCcw size="18" />
          <span v-text="l.domains.refreshBtn"></span>
        </button>
      </header>

      <section class="card no-padding">
        <table class="table">
          <thead>
          <tr>
            <th
                v-for="(value, key) in l.checks.titles"
                :key="key"
                v-text="value"
            ></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="check in checks" :key="check.id">
            <td>
              <CheckCircle v-if="check.is_healthy" class="icon-healthy" size="20" />
              <XCircle v-else class="icon-down" size="20" />
            </td>
            <td>
              <span class="code-badge" v-text="check.status_code || '—'"></span>
            </td>

            <td class="time" v-text="check.response_time + 'с'"></td>

            <td class="date" v-text="new Date(check.created_at).toLocaleString()"></td>

            <td class="error-msg" v-text="check.error_message || ''"></td>
          </tr>
          <tr v-if="checks.length === 0">
            <td colspan="5" class="empty">
              <span v-text="l.checks.empty"></span>
            </td>
          </tr>
          </tbody>
        </table>
      </section>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  position: fixed;
  inset: 0;
  display: flex;
  justify-content: center;
  overflow-y: auto;
  background: #050505;
  font-family: 'Inter', sans-serif;
  color: white;
}

.dashboard::before {
  content: "";
  position: fixed;
  width: 200%; height: 200%; top: -50%; left: -50%;
  background:
      radial-gradient(circle at 30% 30%, #312e81, transparent 40%),
      radial-gradient(circle at 70% 70%, #1e1b4b, transparent 40%);
  filter: blur(120px);
  opacity: 0.6;
  z-index: 0;
  animation: moveGradient 20s infinite alternate;
}

@keyframes moveGradient { from { transform: translate(-5%, -5%) } to { transform: translate(5%, 5%) } }

.container {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 1000px;
  padding: 40px 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}

.brand { display: flex; align-items: center; gap: 15px; }
.brand h1 { font-size: 24px; font-weight: 700; margin: 0; }

.back-btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: white;
  padding: 10px;
  border-radius: 50%;
  cursor: pointer;
  transition: 0.3s;
}

.back-btn:hover { background: rgba(255, 255, 255, 0.15); transform: translateX(-3px); }

.refresh-btn {
  background: transparent;
  border: none;
  color: #818cf8;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.refresh-btn:hover { color: #6366f1; transform: rotate(15deg); }

.card {
  background: rgba(20, 20, 20, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 20px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  overflow: hidden;
}

.table { width: 100%; border-collapse: collapse; }

.table th {
  text-align: left;
  padding: 15px 20px;
  font-size: 11px;
  color: #555;
  text-transform: uppercase;
  letter-spacing: 1px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.table td {
  padding: 18px 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.03);
  font-size: 14px;
}

.icon-healthy { color: #10b981; filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4)); }
.icon-down { color: #ef4444; filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.4)); }

.code-badge {
  background: #0b0b0b;
  border: 1px solid #2a2a2a;
  padding: 4px 10px;
  border-radius: 6px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  color: #aaa;
}

.time { color: #888; }
.date { color: #666; font-size: 13px; }
.error-msg { color: #f87171; font-size: 12px; font-style: italic; max-width: 250px; }

.empty { text-align: center; padding: 60px; color: #444; font-style: italic; }

::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #050505; }
::-webkit-scrollbar-thumb { background: #222; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #333; }
</style>