import asyncio
import logging
import subprocess
import re
import json
import io
from pathlib import Path
from aiogram import Bot, Dispatcher, types, F
from aiogram.filters import Command
from aiogram.fsm.context import FSMContext
from aiogram.fsm.state import State, StatesGroup
from aiogram.fsm.storage.memory import MemoryStorage
from aiogram.types import InlineKeyboardMarkup, InlineKeyboardButton, BufferedInputFile

import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt

# ⚠️ НАСТРОЙКИ
BOT_TOKEN = "8227404687:AAGHcLRwZHS116RhY7TQTJmtNu2MvkCZ3To"
LARAVEL_PATH = Path("C:/OSPanel/home/docsign")
SUPPORT_LINK = "https://t.me/amnvamr"

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

bot = Bot(token=BOT_TOKEN)
storage = MemoryStorage()
dp = Dispatcher(storage=storage)

# 🌍 ЯЗЫКИ
user_languages = {}

def get_user_lang(user_id: int) -> str:
    return user_languages.get(user_id, 'ru')

def set_user_lang(user_id: int, lang: str):
    user_languages[user_id] = lang

# 🌍 ПЕРЕВОДЫ
TRANSLATIONS = {
    'ru': {
        'welcome': '✨ <b>Добро пожаловать в DocSign!</b>\n\n🚀 Платформа электронного документооборота\n\n📋 Выберите действие:',
        'register': '🏢 Регистрация компании',
        'stats': '📊 Статистика',
        'support': '💬 Поддержка',
        'questions': '❓ Есть вопросы?',
        'lang': '🌐 Язык',
        'cancel': '❌ Отмена',
        'back': '🔙 Назад',
        'main_menu': '🏠 Главное меню',
        'name_prompt': '🏢 <b>Шаг 1 из 4</b>\n\nВведите название компании:\n\n💡 Например: Tech Solutions',
        'name_invalid': '❌ Название должно быть от 2 до 50 символов',
        'email_prompt': '📧 <b>Шаг 2 из 4</b>\n\nВведите email:\n\n💡 Email будет использоваться для входа',
        'email_invalid': '❌ Неверный формат email',
        'email_taken': '❌ <b>Email уже зарегистрирован!</b>\n\nИспользуйте другой email.',
        'phone_prompt': '📱 <b>Шаг 3 из 4</b>\n\nВведите номер телефона:\n\n💡 Например: +992901234567',
        'phone_invalid': '❌ Введите корректный номер телефона',
        'phone_taken': '❌ <b>Телефон уже зарегистрирован!</b>\n\nИспользуйте другой номер.',
        'password_prompt': '🔐 <b>Шаг 4 из 4</b>\n\nПридумайте пароль:\n\n💡 Минимум 8 символов',
        'password_short': '❌ Пароль слишком короткий (минимум 8 символов)',
        'confirm': '📋 <b>Проверьте данные:</b>\n\n🏢 Компания: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Телефон: <code>{phone}</code>\n🔐 Пароль: {password}\n\n✅ Всё верно?',
        'create': '✅ Создать компанию',
        'edit': '✏️ Изменить',
        'success': '🎉 <b>Компания создана!</b>\n\n🏢 Название: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Телефон: <code>{phone}</code>\n\n🔐 Сохраните данные для входа.\n\n⚠️ Удалите это сообщение!',
        'stats_title': '📊 <b>Статистика DocSign</b>\n\n',
        'stats_companies': '🏢 Компаний: <b>{total}</b>\n',
        'stats_users': '👥 Пользователей: <b>{total}</b>\n\n',
        'stats_registrations': '📅 <b>Компании:</b>\n• Сегодня: <b>{today}</b>\n• Неделя: <b>{week}</b>\n• Месяц: <b>{month}</b>\n\n',
        'stats_users_reg': '👤 <b>Пользователи:</b>\n• Сегодня: <b>{today}</b>\n• Неделя: <b>{week}</b>\n• Месяц: <b>{month}</b>',
        'stats_error': '❌ Не удалось загрузить статистику',
        'support_text': '💬 <b>Нужна помощь?</b>\n\n👤 @amnvamr\n\n💡 Ответ в течение 24 часов',
        'lang_select': '🌐 <b>Выберите язык:</b>',
        'lang_ru': '🇷🇺 Русский',
        'lang_tj': '🇹🇯 Тоҷикӣ',
        'lang_en': '🇬🇧 English',
        'lang_changed': '✅ Язык изменён',
        'creating_company': '⏳ Создаю компанию...',
        'error': '❌ Ошибка: {error}',
        'checking': '⏳ Проверяю...',
        'chart_company': 'Компании',
        'chart_user': 'Пользователи',
    },
    'tj': {
        'welcome': '✨ <b>Хуш омадед ба DocSign!</b>\n\n🚀 Платформаи ҳуҷҷатҳои электронӣ\n\n📋 Амалро интихоб кунед:',
        'register': '🏢 Бақайдгирии ширкат',
        'stats': '📊 Омор',
        'support': '💬 Дастгирӣ',
        'questions': '❓ Саволҳо?',
        'lang': '🌐 Забон',
        'cancel': '❌ Бекор',
        'back': '🔙 Бозгашт',
        'main_menu': '🏠 Менюи асосӣ',
        'name_prompt': '🏢 <b>Қадами 1 аз 4</b>\n\nНоми ширкатро ворид кунед:',
        'name_invalid': '❌ Ном бояд аз 2 то 50 рамз бошад',
        'email_prompt': '📧 <b>Қадами 2 аз 4</b>\n\nEmail-ро ворид кунед:',
        'email_invalid': '❌ Формати email нодуруст',
        'email_taken': '❌ <b>Email аллакай ба қайд гирифта шудааст!</b>',
        'phone_prompt': '📱 <b>Қадами 3 аз 4</b>\n\nРақами телефонро ворид кунед:',
        'phone_invalid': '❌ Рақами телефонро дуруст ворид кунед',
        'phone_taken': '❌ <b>Телефон аллакай ба қайд гирифта шудааст!</b>',
        'password_prompt': '🔐 <b>Қадами 4 аз 4</b>\n\nПаролро ворид кунед (ҳадди ақал 8 рамз):',
        'password_short': '❌ Парол кӯтоҳ аст',
        'confirm': '📋 <b>Маълумотро санҷед:</b>\n\n🏢 Ширкат: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Телефон: <code>{phone}</code>\n🔐 Парол: {password}\n\n✅ Ҳама дуруст?',
        'create': '✅ Эҷоди ширкат',
        'edit': '✏️ Тағйир',
        'success': '🎉 <b>Ширкат эҷод шуд!</b>\n\n🏢 Ном: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Телефон: <code>{phone}</code>',
        'stats_title': '📊 <b>Омори DocSign</b>\n\n',
        'stats_companies': '🏢 Ширкатҳо: <b>{total}</b>\n',
        'stats_users': '👥 Истифодабарандагон: <b>{total}</b>\n\n',
        'stats_registrations': '📅 <b>Ширкатҳо:</b>\n• Имрӯз: <b>{today}</b>\n• Ҳафта: <b>{week}</b>\n• Моҳ: <b>{month}</b>\n\n',
        'stats_users_reg': '👤 <b>Истифодабарандагон:</b>\n• Имрӯз: <b>{today}</b>\n• Ҳафта: <b>{week}</b>\n• Моҳ: <b>{month}</b>',
        'stats_error': '❌ Оморро бор кардан нашуд',
        'support_text': '💬 <b>Кӯмак лозим?</b>\n\n👤 @amnvamr',
        'lang_select': '🌐 <b>Забонро интихоб кунед:</b>',
        'lang_ru': '🇷🇺 Русский',
        'lang_tj': '🇹🇯 Тоҷикӣ',
        'lang_en': '🇬🇧 English',
        'lang_changed': '✅ Забон тағйир ёфт',
        'creating_company': '⏳ Ширкатро эҷод мекунам...',
        'error': '❌ Хато: {error}',
        'checking': '⏳ Санҷида истодаам...',
        'chart_company': 'Ширкатҳо',
        'chart_user': 'Истифодабарандагон',
    },
    'en': {
        'welcome': '✨ <b>Welcome to DocSign!</b>\n\n🚀 Document management platform\n\n📋 Choose an action:',
        'register': '🏢 Register company',
        'stats': '📊 Statistics',
        'support': '💬 Support',
        'questions': '❓ Questions?',
        'lang': '🌐 Language',
        'cancel': '❌ Cancel',
        'back': '🔙 Back',
        'main_menu': '🏠 Main menu',
        'name_prompt': '🏢 <b>Step 1 of 4</b>\n\nEnter company name:',
        'name_invalid': '❌ Name must be 2-50 characters',
        'email_prompt': '📧 <b>Step 2 of 4</b>\n\nEnter email:',
        'email_invalid': '❌ Invalid email format',
        'email_taken': '❌ <b>Email already registered!</b>',
        'phone_prompt': '📱 <b>Step 3 of 4</b>\n\nEnter phone number:',
        'phone_invalid': '❌ Enter valid phone number',
        'phone_taken': '❌ <b>Phone already registered!</b>',
        'password_prompt': '🔐 <b>Step 4 of 4</b>\n\nCreate password (min 8 chars):',
        'password_short': '❌ Password too short',
        'confirm': '📋 <b>Check your data:</b>\n\n🏢 Company: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Phone: <code>{phone}</code>\n🔐 Password: {password}\n\n✅ All correct?',
        'create': '✅ Create company',
        'edit': '✏️ Edit',
        'success': '🎉 <b>Company created!</b>\n\n🏢 Name: <b>{name}</b>\n📧 Email: <code>{email}</code>\n📱 Phone: <code>{phone}</code>',
        'stats_title': '📊 <b>DocSign Statistics</b>\n\n',
        'stats_companies': '🏢 Companies: <b>{total}</b>\n',
        'stats_users': '👥 Users: <b>{total}</b>\n\n',
        'stats_registrations': '📅 <b>Companies:</b>\n• Today: <b>{today}</b>\n• Week: <b>{week}</b>\n• Month: <b>{month}</b>\n\n',
        'stats_users_reg': '👤 <b>Users:</b>\n• Today: <b>{today}</b>\n• Week: <b>{week}</b>\n• Month: <b>{month}</b>',
        'stats_error': '❌ Failed to load statistics',
        'support_text': '💬 <b>Need help?</b>\n\n👤 @amnvamr',
        'lang_select': '🌐 <b>Select language:</b>',
        'lang_ru': '🇷🇺 Русский',
        'lang_tj': '🇹🇯 Тоҷикӣ',
        'lang_en': '🇬🇧 English',
        'lang_changed': '✅ Language changed',
        'creating_company': '⏳ Creating company...',
        'error': '❌ Error: {error}',
        'checking': '⏳ Checking...',
        'chart_company': 'Companies',
        'chart_user': 'Users',
    }
}

def t(key, lang='ru'):
    return TRANSLATIONS.get(lang, TRANSLATIONS['ru']).get(key, key)

# 🔄 FSM
class CompanyReg(StatesGroup):
    waiting_for_name = State()
    waiting_for_email = State()
    waiting_for_phone = State()
    waiting_for_password = State()
    confirming = State()

# 🔧 ФУНКЦИИ
def run_php(script_name, args=None):
    """Запускает PHP скрипт и возвращает результат"""
    try:
        php_file = LARAVEL_PATH / script_name
        if not php_file.exists():
            logging.error(f"{script_name} not found")
            return None

        cmd = ['php', str(php_file)]
        if args:
            cmd.extend(args)

        result = subprocess.run(
            cmd,
            capture_output=True,
            text=True,
            cwd=str(LARAVEL_PATH),
            timeout=30
        )

        output = result.stdout.strip()
        logging.info(f"{script_name} output: {output}")
        return output
    except Exception as e:
        logging.error(f"Error in {script_name}: {e}")
        return None

def check_email_exists(email: str) -> bool:
    output = run_php("check_email.php", [email])
    return output and output.startswith("EXISTS:")

def check_phone_exists(phone: str) -> bool:
    output = run_php("check_phone.php", [phone])
    return output and output.startswith("EXISTS:")

def create_company(name, admin_name, email, phone, password, telegram_id) -> tuple[bool, str]:
    output = run_php("create_company.php", [name, admin_name, email, phone, password, str(telegram_id)])

    if not output:
        return False, "Script error"

    if output.startswith("OK:"):
        return True, output
    else:
        error_msg = output.replace("ERROR:", "")
        return False, error_msg

def get_stats() -> dict:
    output = run_php("get_stats.php")
    if output and output.startswith("OK:"):
        try:
            return json.loads(output[3:])
        except:
            return {}
    return {}

def is_valid_email(email):
    return bool(re.match(r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$', email))

# 🎯 МЕНЮ
async def show_main_menu(message: types.Message, state: FSMContext):
    await state.clear()
    user_id = message.from_user.id
    lang = get_user_lang(user_id)

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('register', lang), callback_data="register")],
        [InlineKeyboardButton(text=t('stats', lang), callback_data="stats")],
        [InlineKeyboardButton(text=t('support', lang), callback_data="support")],
        [InlineKeyboardButton(text=t('questions', lang), url=SUPPORT_LINK)],
        [InlineKeyboardButton(text=t('lang', lang), callback_data="lang")],
    ])

    await message.answer(t('welcome', lang), reply_markup=kb, parse_mode="HTML")

# 🎯 ХЕНДЛЕРЫ
@dp.message(Command("start"))
async def cmd_start(message: types.Message, state: FSMContext):
    await show_main_menu(message, state)

@dp.callback_query(F.data == "register")
async def cb_register(callback: types.CallbackQuery, state: FSMContext):
    lang = get_user_lang(callback.from_user.id)
    await state.set_state(CompanyReg.waiting_for_name)

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
    ])

    await callback.message.edit_text(t('name_prompt', lang), reply_markup=kb, parse_mode="HTML")
    await callback.answer()

@dp.message(CompanyReg.waiting_for_name)
async def process_name(message: types.Message, state: FSMContext):
    lang = get_user_lang(message.from_user.id)
    name = message.text.strip()

    if len(name) < 2 or len(name) > 50:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await message.answer(t('name_invalid', lang), reply_markup=kb, parse_mode="HTML")
        return

    await state.update_data(name=name)
    await state.set_state(CompanyReg.waiting_for_email)

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
    ])
    await message.answer(
        f"✅ <b>{name}</b>\n\n" + t('email_prompt', lang),
        reply_markup=kb,
        parse_mode="HTML"
    )

@dp.message(CompanyReg.waiting_for_email)
async def process_email(message: types.Message, state: FSMContext):
    lang = get_user_lang(message.from_user.id)
    email = message.text.strip().lower()

    if not is_valid_email(email):
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await message.answer(t('email_invalid', lang), reply_markup=kb, parse_mode="HTML")
        return

    checking_msg = await message.answer(t('checking', lang))
    if check_email_exists(email):
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await checking_msg.edit_text(t('email_taken', lang), reply_markup=kb, parse_mode="HTML")
        return

    await checking_msg.delete()
    await state.update_data(email=email)
    await state.set_state(CompanyReg.waiting_for_phone)

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
    ])
    await message.answer(
        f"✅ <code>{email}</code>\n\n" + t('phone_prompt', lang),
        reply_markup=kb,
        parse_mode="HTML"
    )

@dp.message(CompanyReg.waiting_for_phone)
async def process_phone(message: types.Message, state: FSMContext):
    lang = get_user_lang(message.from_user.id)
    phone = message.text.strip()

    if len(phone) < 7:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await message.answer(t('phone_invalid', lang), reply_markup=kb, parse_mode="HTML")
        return

    checking_msg = await message.answer(t('checking', lang))
    if check_phone_exists(phone):
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await checking_msg.edit_text(t('phone_taken', lang), reply_markup=kb, parse_mode="HTML")
        return

    await checking_msg.delete()
    await state.update_data(phone=phone)
    await state.set_state(CompanyReg.waiting_for_password)

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
    ])
    await message.answer(
        f"✅ <code>{phone}</code>\n\n" + t('password_prompt', lang),
        reply_markup=kb,
        parse_mode="HTML"
    )

@dp.message(CompanyReg.waiting_for_password)
async def process_password(message: types.Message, state: FSMContext):
    lang = get_user_lang(message.from_user.id)
    password = message.text.strip()

    if len(password) < 8:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")]
        ])
        await message.answer(t('password_short', lang), reply_markup=kb, parse_mode="HTML")
        return

    await state.update_data(password=password)
    await state.set_state(CompanyReg.confirming)

    data = await state.get_data()
    masked_pwd = '•' * len(password) + f" ({len(password)} символов)"

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('create', lang), callback_data="confirm_create")],
        [InlineKeyboardButton(text=t('cancel', lang), callback_data="main_menu")],
    ])

    await message.answer(
        t('confirm', lang).format(
            name=data['name'],
            email=data['email'],
            phone=data['phone'],
            password=masked_pwd
        ),
        reply_markup=kb,
        parse_mode="HTML"
    )

@dp.callback_query(F.data == "confirm_create")
async def cb_confirm_create(callback: types.CallbackQuery, state: FSMContext):
    lang = get_user_lang(callback.from_user.id)
    data = await state.get_data()

    await callback.message.edit_text(t('creating_company', lang))

    success, result = create_company(
        data['name'],
        data['name'],  # admin_name = company name
        data['email'],
        data['phone'],
        data['password'],
        callback.from_user.id
    )

    if success:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('main_menu', lang), callback_data="main_menu")]
        ])
        await callback.message.edit_text(
            t('success', lang).format(
                name=data['name'],
                email=data['email'],
                phone=data['phone']
            ),
            reply_markup=kb,
            parse_mode="HTML"
        )
    else:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('main_menu', lang), callback_data="main_menu")]
        ])
        await callback.message.edit_text(
            t('error', lang).format(error=result),
            reply_markup=kb,
            parse_mode="HTML"
        )

    await state.clear()
    await callback.answer()

@dp.callback_query(F.data == "stats")
async def cb_stats(callback: types.CallbackQuery):
    lang = get_user_lang(callback.from_user.id)
    loading_msg = await callback.message.edit_text("⏳")

    stats = get_stats()

    if not stats:
        kb = InlineKeyboardMarkup(inline_keyboard=[
            [InlineKeyboardButton(text=t('main_menu', lang), callback_data="main_menu")]
        ])
        await loading_msg.edit_text(t('stats_error', lang), reply_markup=kb, parse_mode="HTML")
        await callback.answer()
        return

    total_companies = stats.get('total_companies', 0)
    total_users = stats.get('total_users', 0)

    labels = [t('chart_company', lang), t('chart_user', lang)]
    sizes = [total_companies, total_users]

    if sum(sizes) == 0:
        sizes = [1, 1]

    fig, ax = plt.subplots(figsize=(6, 6))
    colors = ['#3498db', '#2ecc71']
    ax.pie(sizes, labels=labels, autopct='%1.1f%%', startangle=90, colors=colors, textprops={'fontsize': 12})
    ax.axis('equal')

    buf = io.BytesIO()
    plt.savefig(buf, format='png', bbox_inches='tight')
    plt.close(fig)
    buf.seek(0)

    text = t('stats_title', lang)
    text += t('stats_companies', lang).format(total=total_companies)
    text += t('stats_users', lang).format(total=total_users)
    text += t('stats_registrations', lang).format(
        today=stats.get('companies_today', 0),
        week=stats.get('companies_week', 0),
        month=stats.get('companies_month', 0)
    )
    text += t('stats_users_reg', lang).format(
        today=stats.get('users_today', 0),
        week=stats.get('users_week', 0),
        month=stats.get('users_month', 0)
    )

    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('main_menu', lang), callback_data="main_menu")]
    ])

    try:
        await loading_msg.delete()
    except:
        pass

    photo = BufferedInputFile(buf.read(), filename="stats.png")
    await callback.message.answer_photo(photo=photo, caption=text, reply_markup=kb, parse_mode="HTML")
    await callback.answer()

@dp.callback_query(F.data == "support")
async def cb_support(callback: types.CallbackQuery):
    lang = get_user_lang(callback.from_user.id)
    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text="💬 @amnvamr", url=SUPPORT_LINK)],
        [InlineKeyboardButton(text=t('main_menu', lang), callback_data="main_menu")]
    ])
    await callback.message.edit_text(t('support_text', lang), reply_markup=kb, parse_mode="HTML")
    await callback.answer()

@dp.callback_query(F.data == "lang")
async def cb_lang(callback: types.CallbackQuery):
    lang = get_user_lang(callback.from_user.id)
    kb = InlineKeyboardMarkup(inline_keyboard=[
        [InlineKeyboardButton(text=t('lang_ru', lang), callback_data="lang_ru")],
        [InlineKeyboardButton(text=t('lang_tj', lang), callback_data="lang_tj")],
        [InlineKeyboardButton(text=t('lang_en', lang), callback_data="lang_en")],
        [InlineKeyboardButton(text=t('back', lang), callback_data="main_menu")]
    ])
    await callback.message.edit_text(t('lang_select', lang), reply_markup=kb, parse_mode="HTML")
    await callback.answer()

@dp.callback_query(F.data.startswith("lang_"))
async def cb_set_lang(callback: types.CallbackQuery, state: FSMContext):
    lang_code = callback.data.replace("lang_", "")
    set_user_lang(callback.from_user.id, lang_code)

    await callback.answer(t('lang_changed', lang_code))
    await state.clear()

    try:
        await callback.message.delete()
    except:
        pass

    await show_main_menu(callback.message, state)

@dp.callback_query(F.data == "main_menu")
async def cb_main_menu(callback: types.CallbackQuery, state: FSMContext):
    await state.clear()
    try:
        await callback.message.delete()
    except:
        pass
    await show_main_menu(callback.message, state)
    await callback.answer()

@dp.message(Command("cancel"))
async def cmd_cancel(message: types.Message, state: FSMContext):
    await show_main_menu(message, state)

# 🚀 ЗАПУСК
async def main():
    logging.info("Starting DocSign bot...")
    logging.info(f"Laravel path: {LARAVEL_PATH}")

    for f in ['check_email.php', 'check_phone.php', 'create_company.php', 'get_stats.php']:
        exists = (LARAVEL_PATH / f).exists()
        logging.info(f"  {f}: {'✓' if exists else '✗'}")

    await dp.start_polling(bot)

if __name__ == "__main__":
    asyncio.run(main())