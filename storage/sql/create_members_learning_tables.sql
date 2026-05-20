CREATE TABLE IF NOT EXISTS member_learning_preferences (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    member_id INT UNSIGNED NOT NULL,
    preferred_learning_style ENUM('reading', 'visual', 'audio', 'quiz-first', 'scenario-based', 'reflection-based') NOT NULL DEFAULT 'reading',
    confidence_level ENUM('beginner', 'familiar', 'confident') NOT NULL DEFAULT 'beginner',
    current_selected_question_id BIGINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_member_learning_preferences_member (member_id),
    CONSTRAINT fk_member_learning_preferences_member
        FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS learning_event_overviews (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    question_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    intro_copy TEXT NOT NULL,
    what_you_will_learn_json LONGTEXT NOT NULL,
    prepare_checklist_json LONGTEXT NOT NULL,
    suggested_questions_json LONGTEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_learning_event_overviews_question (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_modules (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    question_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(180) NOT NULL,
    title VARCHAR(255) NOT NULL,
    short_description VARCHAR(300) NOT NULL,
    lesson_content TEXT NOT NULL,
    key_takeaway TEXT NOT NULL,
    reflection_prompt TEXT NOT NULL,
    lesson_order INT UNSIGNED NOT NULL,
    badge_name VARCHAR(160) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_lesson_modules_slug_question (question_id, slug),
    UNIQUE KEY uq_lesson_modules_order_question (question_id, lesson_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_quizzes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    lesson_module_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('A', 'B', 'C', 'D') NOT NULL,
    explanation_text TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_lesson_quizzes_module (lesson_module_id),
    CONSTRAINT fk_lesson_quizzes_lesson_module
        FOREIGN KEY (lesson_module_id) REFERENCES lesson_modules(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS member_lesson_progress (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    member_id INT UNSIGNED NOT NULL,
    lesson_module_id BIGINT UNSIGNED NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'not_started',
    reflection_text TEXT DEFAULT NULL,
    completed_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_member_lesson_progress_member_lesson (member_id, lesson_module_id),
    KEY idx_member_lesson_progress_member (member_id),
    CONSTRAINT fk_member_lesson_progress_member
        FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_member_lesson_progress_lesson_module
        FOREIGN KEY (lesson_module_id) REFERENCES lesson_modules(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS member_badges (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    member_id INT UNSIGNED NOT NULL,
    badge_key VARCHAR(120) NOT NULL,
    badge_name VARCHAR(180) NOT NULL,
    badge_description VARCHAR(255) NOT NULL,
    earned_at DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_member_badges_member_key (member_id, badge_key),
    KEY idx_member_badges_member (member_id),
    CONSTRAINT fk_member_badges_member
        FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS member_saved_questions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    member_id INT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    lesson_module_id BIGINT UNSIGNED DEFAULT NULL,
    saved_question_text TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_member_saved_questions_dedupe (member_id, question_id, lesson_module_id, saved_question_text(120)),
    KEY idx_member_saved_questions_member_question (member_id, question_id),
    CONSTRAINT fk_member_saved_questions_member
        FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_member_saved_questions_lesson_module
        FOREIGN KEY (lesson_module_id) REFERENCES lesson_modules(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS member_prepare_progress (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    member_id INT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    checklist_json LONGTEXT NOT NULL,
    notes_text TEXT DEFAULT NULL,
    is_ready TINYINT(1) NOT NULL DEFAULT 0,
    ready_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_member_prepare_progress_member_question (member_id, question_id),
    CONSTRAINT fk_member_prepare_progress_member
        FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
