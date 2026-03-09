export default {
    titles: {
        head: 'Domain Monitor',
        mid: 'Domain & SSL monitoring platform'
    },
    errors: {
        fillFields: 'Пожалуйста, заполните все обязательные поля',
        email: 'Введите корректный адрес электронной почты',
        name: 'Пожалуйста, введите ваше имя',
        password: 'Пароль должен быть не менее 6 символов',
        passwordConf: 'Пароли не совпадают',
        err: 'Ошибка операции',
        errLoad: 'Ошибка загрузки',
        errAdd: 'Ошибка при добавлении'
    },
    auth: {
        login: 'Войти',
        logout: 'Выйти',
        register: 'Зарегистрироваться',
        email: 'Электронная почта',
        password: 'Пароль',
        name: 'Ваше имя',
        noAccount: 'Нет аккаунта? Регистрация',
        hasAccount: 'Уже есть аккаунт? Войти',
        confirmDelete: 'Удалить этот домен из мониторинга?'
    },
    domains: {
        title: 'Мониторинг доменов',
        addBtn: 'Добавить',
        refreshBtn: 'Обновить',
        addDomain: 'Добавить домен',
        urlLabel: 'URL (с https://)',
        interval: 'Интервал (мин)',
        timeout: 'Таймаут (сек)',
        table: {
            name: 'Домен',
            settings: 'Настройки',
            actions: 'Действия',
            history: 'История',
            empty: 'Нет добавленных доменов'
        }
    },
    checks: {
        title: 'История проверок',
        empty: 'История проверок пуста...',
        titles: {
            status: 'Статус',
            code: 'Код',
            response: 'Ответ (сек)',
            date: 'Дата',
            error: 'Ошибка'
        }

    },
    placeholders: {
        url: 'https://google.com'
    }
}