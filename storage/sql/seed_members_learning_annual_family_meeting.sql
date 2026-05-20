INSERT INTO learning_event_overviews (
    question_id,
    title,
    intro_copy,
    what_you_will_learn_json,
    prepare_checklist_json,
    suggested_questions_json
)
SELECT
    q.id,
    'Annual family meeting coming up - what should I ask?',
    'Family meetings can feel intimidating, especially when wealth, values, expectations, advisors, and future responsibilities are part of the conversation.
This journey helps the user understand what annual family meetings are for, what topics usually come up, what questions are useful to ask, and how to prepare with confidence.',
    JSON_ARRAY(
        'Why annual family meetings exist',
        'What topics matter most',
        'How decisions, values, and expectations are discussed',
        'Which questions are thoughtful and productive',
        'How to prepare before the meeting'
    ),
    JSON_ARRAY(
        'Understand the purpose of the meeting',
        'Know the main topics on the agenda',
        'Understand the family''s values and priorities',
        'Understand how decisions are made',
        'Know who the advisors are and what they do',
        'Prepare 3 to 5 thoughtful questions',
        'Write down anything that feels unclear or sensitive',
        'Leave the meeting knowing the next steps'
    ),
    JSON_ARRAY(
        'What is the main goal of this year''s family meeting?',
        'What values are guiding the decisions we make as a family?',
        'What is the purpose of our family''s wealth?',
        'What can I expect from our family''s wealth over time?',
        'What is expected of me as I get older?',
        'How are major family decisions made?',
        'Who are our advisors, and what does each person do?',
        'How are younger family members included in discussions and decisions?',
        'How does our family approach philanthropy or community impact?',
        'What topics should be discussed together as a family, and what topics are handled privately?',
        'What follow-up actions should come out of this meeting?',
        'What do we want future generations to understand about this family?'
    )
FROM questions q
WHERE q.question = 'Annual family meeting coming up - what should I ask?'
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    intro_copy = VALUES(intro_copy),
    what_you_will_learn_json = VALUES(what_you_will_learn_json),
    prepare_checklist_json = VALUES(prepare_checklist_json),
    suggested_questions_json = VALUES(suggested_questions_json);

INSERT INTO lesson_modules (
    question_id, slug, title, short_description, lesson_content, key_takeaway, reflection_prompt, lesson_order, badge_name
)
SELECT q.id, x.slug, x.title, x.short_description, x.lesson_content, x.key_takeaway, x.reflection_prompt, x.lesson_order, x.badge_name
FROM questions q
JOIN (
    SELECT 'annual-family-meeting-foundations' AS slug, 'Why annual family meetings matter' AS title,
           'Learn what an annual family meeting is meant to accomplish.' AS short_description,
           'Annual family meetings are not only about money.
They are meant to help family members build trust, share information over time, understand what matters to the family, and talk about the future in a structured way.
A good meeting gives people clarity, reduces confusion, and creates space for questions that might otherwise feel uncomfortable.
The goal is not for every person to know everything at once.
The goal is to help the family stay aligned and make sure younger generations understand both opportunities and responsibilities.' AS lesson_content,
           'A strong family meeting creates clarity, trust, and shared understanding.' AS key_takeaway,
           'What do I most hope to understand by the end of a family meeting?' AS reflection_prompt,
           1 AS lesson_order, 'First Lesson Completed' AS badge_name
    UNION ALL
    SELECT 'family-values-and-purpose', 'Values, purpose, and what wealth means',
           'Understand the bigger questions behind the meeting.',
           'Many successful family meetings are built around deeper questions, not just numbers.
Families often need to talk about what their wealth is for, what values they want to protect, what matters most, and what kind of impact they want to have.
This helps younger family members understand the purpose behind decisions, not just the outcomes.
When values are clear, conversations about spending, investing, giving, education, and responsibility become easier to understand.',
           'Useful questions often begin with values and purpose, not entitlement.',
           'What values do I think are most important to my family, and which ones feel unclear to me?',
           2, 'Values Explorer'
    UNION ALL
    SELECT 'governance-advisors-and-decisions', 'How decisions get made',
           'Learn the basics of governance, roles, and advisors.',
           'Family meetings often include more than personal opinions.
They may involve governance structures, recurring agendas, decision-making processes, and outside advisors.
It is useful to understand who helps the family, what each advisor does, and how major decisions are made.
Not every topic is decided in the same way.
Some topics are for discussion, some are for education, and some result in clear action items after the meeting.',
           'Understanding the process is just as important as understanding the topic.',
           'Which part feels least clear to me right now: who decides, who advises, or how follow-up happens?',
           3, 'Governance Learner'
    UNION ALL
    SELECT 'expectations-and-next-generation-role', 'What can I expect, and what is expected of me?',
           'Explore responsibility, participation, and next-generation involvement.',
           'One of the most important parts of a family meeting is understanding expectations.
A young family member may wonder what support is available, what future involvement might look like, and what responsibilities come with belonging to the family system.
This can include education, communication, behavior, stewardship, participation in future meetings, or eventual roles in philanthropy, governance, or a family business.
Asking about expectations is thoughtful.
It shows maturity and a desire to understand the bigger picture.',
           'A good question is not only “What can I expect?” but also “What is expected of me?”',
           'What responsibilities might matter in my family even if nobody has explained them clearly yet?',
           4, 'Thoughtful Participant'
    UNION ALL
    SELECT 'building-your-question-list', 'Building thoughtful questions for the meeting',
           'Turn your learning into practical questions you can actually ask.',
           'Good family-meeting questions are open, respectful, and specific.
They are meant to create understanding, not confrontation.
It helps to ask about purpose, values, decisions, expectations, advisors, and next steps.
A strong question invites clarity.
A weak question usually sounds accusatory, vague, or focused only on immediate personal gain.',
           'The best questions are respectful, open-ended, and grounded in curiosity.',
           'Write 3 questions I could realistically ask at the next family meeting.',
           5, 'Question Builder'
) x
WHERE q.question = 'Annual family meeting coming up - what should I ask?'
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    short_description = VALUES(short_description),
    lesson_content = VALUES(lesson_content),
    key_takeaway = VALUES(key_takeaway),
    reflection_prompt = VALUES(reflection_prompt),
    badge_name = VALUES(badge_name);

INSERT INTO lesson_quizzes (
    lesson_module_id, question_text, option_a, option_b, option_c, option_d, correct_option, explanation_text
)
SELECT lm.id, qz.question_text, qz.option_a, qz.option_b, qz.option_c, qz.option_d, qz.correct_option, qz.explanation_text
FROM lesson_modules lm
JOIN questions q ON q.id = lm.question_id
JOIN (
    SELECT 'annual-family-meeting-foundations' AS slug,
           'What is the main purpose of an annual family meeting?' AS question_text,
           'To review investment returns only' AS option_a,
           'To create shared understanding and alignment' AS option_b,
           'To decide who gets money next' AS option_c,
           'To avoid difficult conversations' AS option_d,
           'B' AS correct_option,
           'The best meetings help the family build trust, share information gradually, and stay aligned on purpose, expectations, and future direction.' AS explanation_text
    UNION ALL
    SELECT 'family-values-and-purpose',
           'Which question gets closest to the heart of a strong family meeting?',
           'How much money is there exactly?',
           'What are the core values guiding our family decisions?',
           'Who is in charge of everything?',
           'When do I receive something?',
           'B',
           'Values and purpose create the framework that makes later conversations about wealth, responsibility, and legacy more meaningful.'
    UNION ALL
    SELECT 'governance-advisors-and-decisions',
           'Why is it helpful to understand the roles of advisors and decision-making structures?',
           'So you can challenge every decision immediately',
           'So you know how the family organizes important conversations and responsibilities',
           'So family meetings can be shorter than five minutes',
           'So no one has to ask questions',
           'B',
           'When users understand who is involved and how decisions happen, they can ask smarter questions and follow the conversation with more confidence.'
    UNION ALL
    SELECT 'expectations-and-next-generation-role',
           'Which question shows the most maturity in a family meeting?',
           'What do I get?',
           'What is expected of me as I get older?',
           'Why is this so complicated?',
           'Can we skip this part?',
           'B',
           'Questions about responsibility, stewardship, and participation show a genuine interest in understanding the family''s expectations and values.'
    UNION ALL
    SELECT 'building-your-question-list',
           'Which is the strongest question to bring to a family meeting?',
           'When do I get my share?',
           'Who made this rule and why?',
           'What is the goal of this meeting, and what do you hope each generation understands from it?',
           'Why was I not told everything earlier?',
           'C',
           'This question is respectful, open-ended, and focused on understanding purpose and intergenerational communication.'
) qz ON qz.slug = lm.slug
WHERE q.question = 'Annual family meeting coming up - what should I ask?'
ON DUPLICATE KEY UPDATE
    question_text = VALUES(question_text),
    option_a = VALUES(option_a),
    option_b = VALUES(option_b),
    option_c = VALUES(option_c),
    option_d = VALUES(option_d),
    correct_option = VALUES(correct_option),
    explanation_text = VALUES(explanation_text);
