<script setup lang="ts">
import {ref, onMounted} from 'vue';
import api from '../api/axios';
import {useRouter} from 'vue-router';
import locale from '../locales/ru.ts'
import {LayoutDashboard, Plus, Trash2, Edit, History, ExternalLink, User} from 'lucide-vue-next';

const domains = ref([]);
const router = useRouter();
const user = ref<{ name: string; email: string } | null>(null);
const newDomain = ref({
  name: '',
  check_interval: 1,
  timeout: 10
});
const l = locale
const editingId = ref<number | null>(null);
const editForm = ref({name: '', check_interval: 1, timeout: 10});

const fetchUser = async () => {
  try {
    const {data} = await api.get('/user');
    user.value = data;
  } catch (e) {
    console.error(l.errors.errProfile);
  }
};
const startEdit = (domain: any) => {
  editingId.value = domain.id;
  editForm.value = {...domain};
};

const cancelEdit = () => {
  editingId.value = null;
};

const updateDomain = async () => {
  if (!editingId.value) return;
  const payload = {
    name: editForm.value.name,
    check_interval: editForm.value.check_interval,
    timeout: editForm.value.timeout
  }
  try {
    await api.put(`/domains/${editingId.value}`, payload);
    editingId.value = null;
    await fetchDomains();
  } catch (e: any) {
    alert(e.response?.data?.message || l.errors.errUpdate);
  }
};

const fetchDomains = async () => {
  try {
    const {data} = await api.get('/domains');
    domains.value = data;
  } catch (e) {
    console.error(l.errors.errLoad);
  }
};

const addDomain = async () => {
  try {
    await api.post('/domains', newDomain.value);
    newDomain.value = {name: '', check_interval: 1, timeout: 10};
    await fetchDomains();
  } catch (e: any) {
    alert(e.response?.data?.message || l.errors.errAdd);
  }
};

const deleteDomain = async (id: number) => {
  if (confirm(l.auth.confirmDelete)) {
    await api.delete(`/domains/${id}`);
    await fetchDomains();
  }
};

const handleLogout = () => {
  localStorage.removeItem('token');
  delete api.defaults.headers.common['Authorization'];
  router.push('/login');
};

onMounted(() => {
  fetchDomains();
  fetchUser();
});

</script>

<template>
  <div class="dashboard">
    <div class="container">
      <header class="header">
        <h1>
          <LayoutDashboard size="22"/>
          <span v-text="l.titles.head"></span>
        </h1>
        <div class="header-actions">
          <User size="18" class="user-icon" />
          <span
            v-if="user"
            class="user-info"
            v-text="user.name">
          </span>
          <button
            class="logout"
            @click="handleLogout"
            v-text="l.auth.logout"/>
        </div>
      </header>

      <section class="card">

        <h2 class="section-title">
          <Plus size="18"/>
          <span v-text="l.domains.addDomain"></span>
        </h2>

        <form
          @submit.prevent="addDomain"
          class="form-grid">

          <div class="input">
            <label v-text="l.domains.urlLabel"/>
            <input
              v-model="newDomain.name"
              type="url"
              :placeholder="l.placeholders.url"
              required
            />
          </div>

          <div class="input">
            <label v-text="l.domains.interval"/>
            <input
                v-model="newDomain.check_interval"
                type="number"
                min="1"
            />
          </div>

          <div class="input">
            <label v-text="l.domains.timeout"/>
            <input
                v-model="newDomain.timeout"
                type="number"
                min="1"
            />
          </div>
          <button
            class="primary"
            v-text="l.domains.addBtn"
          />
        </form>

      </section>

      <section class="card">

        <table class="table">
          <thead>
          <tr>
            <th v-text="l.domains.table.name"/>
            <th v-text="l.domains.table.settings"/>
            <th v-text="l.domains.table.actions"/>
          </tr>
          </thead>

          <tbody>
          <tr v-for="domain in domains" :key="domain.id">
            <td class="domain">
              <template v-if="editingId === domain.id">
                <input
                  v-model="editForm.name"
                  type="url"
                  class="small-input"
                />
              </template>
              <template v-else>
                <span v-text="domain.name"></span>
                <a :href="domain.name" target="_blank">
                  <ExternalLink size="14"/>
                </a>
              </template>
            </td>

            <td class="settings">
              <template v-if="editingId === domain.id">
                <div class="edit-group">
                  <input
                    v-model="editForm.check_interval"
                    type="number"
                    title="Интервал"
                  />
                  <input
                    v-model="editForm.timeout"
                    type="number"
                    title="Таймаут"
                  />
                </div>
              </template>
              <template v-else>
                <span
                  v-text="`${domain.check_interval} ${l.domains.min} / ${domain.timeout} ${l.domains.sec}`">
                </span>
              </template>
            </td>

            <td class="actions">
              <template v-if="editingId === domain.id">
                <button
                  class="primary small"
                  @click="updateDomain"
                  v-text="l.domains.ok"
                />
                <button
                  class="link small"
                  @click="cancelEdit"
                  v-text="l.domains.cancel"
                />
              </template>
              <template v-else>
                <button class="link" @click="startEdit(domain)">
                  <Edit size="16"/>
                </button>
                <button class="link" @click="router.push(`/domains/${domain.id}/checks`)">
                  <History size="16"/>
                </button>
                <button class="danger" @click="deleteDomain(domain.id)">
                  <Trash2 size="18"/>
                </button>
              </template>
            </td>
          </tr>

          <tr v-if="domains.length === 0">
            <td
              colspan="3"
              class="empty"
              v-text="l.domains.table.empty"/>
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
  font-family: 'Inter', system-ui, sans-serif;
  color: white;
}

.dashboard::before {
  content: "";
  position: fixed;
  width: 200%;
  height: 200%;
  top: -50%;
  left: -50%;
  background: radial-gradient(circle at 30% 30%, #312e81, transparent 40%),
  radial-gradient(circle at 70% 70%, #1e1b4b, transparent 40%);
  filter: blur(120px);
  opacity: 0.6;
  z-index: 0;
  animation: moveGradient 20s infinite alternate;
}

@keyframes moveGradient {
  from {
    transform: translate(-5%, -5%)
  }
  to {
    transform: translate(5%, 5%)
  }
}

.container {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 1100px;
  padding: 40px 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}

.header h1 {
  font-size: 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 700;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
  font-weight: 500;
}

.user-icon {
  opacity: 0.8;
}

.card {
  background: rgba(20, 20, 20, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.section-title {
  font-size: 18px;
  margin-bottom: 25px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #efefef;
}

.form-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 140px;
  gap: 20px;
  align-items: flex-end;
}

.input {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.input label {
  font-size: 12px;
  color: #888;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.input input {
  background: #0b0b0b;
  border: 1px solid #2a2a2a;
  border-radius: 12px;
  padding: 12px 16px;
  color: white;
  transition: all 0.3s;
}

.input input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th {
  text-align: left;
  padding: 15px;
  font-size: 12px;
  color: #555;
  text-transform: uppercase;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.table td {
  padding: 20px 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.03);
}

.domain {
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
}

.domain a {
  color: #6366f1;
  opacity: 0.6;
  transition: 0.3s;
}

.domain a:hover {
  opacity: 1;
}

.settings {
  color: #999;
  font-size: 14px;
}

.empty {
  text-align: center;
  padding: 40px;
  color: #444;
}

/* Buttons */
button {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.25s;
}

.actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.primary {
  background: linear-gradient(135deg, #6366f1, #4f46e5);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 12px;
  font-weight: 600;
  width: 100%;
}

.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
}

.logout {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #ccc;
  padding: 8px 18px;
  border-radius: 10px;
  font-size: 14px;
}

.logout:hover {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border-color: rgba(239, 68, 68, 0.2);
}

.link {
  background: rgba(99, 102, 241, 0.1);
  border: 1px solid rgba(99, 102, 241, 0.2);
  color: #818cf8;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
}

.link:hover {
  background: rgba(99, 102, 241, 0.2);
}

.danger {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  color: #f87171;
  padding: 8px;
  border-radius: 8px;
}

.danger:hover {
  background: rgba(239, 68, 68, 0.2);
}

.small-input {
  background: #0b0b0b;
  border: 1px solid #2a2a2a;
  border-radius: 8px;
  padding: 8px 12px;
  color: white;
  font-size: 14px;
  width: 100%;
  transition: all 0.3s;
}

.small-input:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 10px rgba(99, 102, 241, 0.15);
}

.edit-group {
  display: flex;
  gap: 10px;
  align-items: center;
}

.edit-group input {
  width: 80px;
  background: #0b0b0b;
  border: 1px solid #2a2a2a;
  border-radius: 8px;
  padding: 8px;
  color: white;
  text-align: center;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>