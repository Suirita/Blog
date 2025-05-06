import { createI18n } from 'vue-i18n';
import en from './lang/en.json';
import fr from './lang/fr.json';

const messages = {
    en,
    fr,
};

const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages,
});

export default i18n;
