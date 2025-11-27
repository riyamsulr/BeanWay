<?php
session_start();

// 1. كود الحماية: إذا لم يكن مسجلاً، حوله لصفحة الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php'; 
?>

<style>
    /* تنسيقات الكويز */
    .quiz-wrap { max-width: 800px; margin: 50px auto; padding: 0 20px; }
    
    .quiz-card {
        background: #FFFFFF; border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        overflow: hidden; border: 1px solid #E6D7C3;
    }
    
    .quiz-header {
        background-color: #fffaf0; padding: 30px; text-align: center; border-bottom: 1px solid #eee;
    }
    .quiz-header h2 { font-family: "Playfair Display", serif; font-size: 28px; color: #124D43; margin-bottom: 10px; }
    .quiz-header p { color: #6b5a46; }

    .quiz-body { padding: 30px; }
    
    .progress { height: 8px; background: #efe7db; border-radius: 10px; overflow: hidden; margin-bottom: 25px; }
    .progress > i { display: block; height: 100%; width: 0%; background: #124D43; transition: width 0.4s ease; }

    .question { display: none; animation: fadeIn 0.4s ease; }
    .question.active { display: block; }
    .q-text { font-weight: 600; font-size: 18px; margin-bottom: 20px; color: #124D43; }
    
    .options { display: flex; flex-direction: column; gap: 12px; }
    .option-btn {
        background: #fff; border: 1px solid #E6D7C3; padding: 15px; border-radius: 10px;
        cursor: pointer; text-align: left; font-size: 15px; transition: 0.2s; color: #555;
    }
    .option-btn:hover {
        background-color: #fcf8f2; border-color: #124D43; color: #124D43; transform: translateX(5px);
    }

    .result { display: none; padding: 30px; text-align: center; }
    .result.active { display: block; }
    .result h3 { font-family: "Playfair Display", serif; color: #124D43; margin-bottom: 15px; font-size: 24px; }
    .type-badge {
        display: inline-block; padding: 10px 20px; border-radius: 50px;
        background: #124D43; color: #fff; font-weight: bold; font-size: 20px; margin-bottom: 20px;
    }
    .result-actions { margin-top: 25px; display: flex; gap: 15px; justify-content: center; }
    
    .btn-action {
        padding: 10px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; text-decoration: none;
    }
    .btn-retry { background: #E6D7C3; color: #4a3b28; }
    .btn-recipes { background: #124D43; color: #fff; }

    .nav-controls { margin-top: 30px; display: flex; justify-content: space-between; }
    .back-btn { background: none; border: none; color: #888; cursor: pointer; font-size: 14px; }
    .back-btn:hover { color: #124D43; text-decoration: underline; }
    .back-btn:disabled { opacity: 0; cursor: default; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<main>
  <div class="quiz-wrap">
    <div class="quiz-card">
      
      <div class="quiz-header">
        <h2>What coffee suits your mood?</h2>
        <p>Answer 6 quick questions to find your perfect cup for today.</p>
      </div>

      <div class="quiz-body">
        <div class="progress"><i id="progress-bar"></i></div>

        <div class="question active" data-q="1">
          <div class="q-text">1. What kind of vibe are you in today?</div>
          <div class="options">
            <button class="option-btn" data-types="espresso,americano">"I need something to <strong>wake me up</strong>."</button>
            <button class="option-btn" data-types="latte">"I want something <strong>comforting and warm</strong>."</button>
            <button class="option-btn" data-types="mocha">"I'm craving something <strong>sweet and cozy</strong>."</button>
            <button class="option-btn" data-types="iced">"Something <strong>refreshing or chilled</strong> sounds nice."</button>
          </div>
        </div>

        <div class="question" data-q="2">
          <div class="q-text">2. What’s the weather like?</div>
          <div class="options">
            <button class="option-btn" data-types="iced" data-weather="iced">"Hot — I need to cool down."</button>
            <button class="option-btn" data-types="latte,mocha,cappuccino" data-weather="warm">"Cold or rainy — something warm is needed."</button>
            <button class="option-btn" data-types="espresso,americano" data-weather="neutral">"Neutral — doesn't matter."</button>
          </div>
        </div>

        <div class="question" data-q="3">
          <div class="q-text">3. What kind of coffee experience do you want?</div>
          <div class="options">
            <button class="option-btn" data-types="espresso,americano" data-experience="simple">"Quick and simple — just caffeine."</button>
            <button class="option-btn" data-types="latte,cappuccino" data-experience="cozy">"Calm and cozy — slow sipping."</button>
            <button class="option-btn" data-types="mocha,iced" data-experience="treat">"Fun and indulgent — a treat."</button>
          </div>
        </div>

        <div class="question" data-q="4">
          <div class="q-text">4. How strong do you like your coffee flavor?</div>
          <div class="options">
            <button class="option-btn" data-types="espresso,americano">"Strong and bold."</button>
            <button class="option-btn" data-types="latte,cappuccino">"Mild and milky."</button>
            <button class="option-btn" data-types="iced">"Light and refreshing."</button>
            <button class="option-btn" data-types="mocha">"Sweet and chocolatey."</button>
          </div>
        </div>

        <div class="question" data-q="5">
          <div class="q-text">5. What are you doing right now?</div>
          <div class="options">
            <button class="option-btn" data-types="espresso,americano">"Working or studying — need focus."</button>
            <button class="option-btn" data-types="latte,cappuccino">"Relaxing or chatting."</button>
            <button class="option-btn" data-types="iced">"On the go / Driving."</button>
            <button class="option-btn" data-types="mocha">"Dessert time."</button>
          </div>
        </div>

        <div class="question" data-q="6">
          <div class="q-text">6. How do you want to feel afterwards?</div>
          <div class="options">
            <button class="option-btn" data-types="espresso,americano">"Energized and sharp."</button>
            <button class="option-btn" data-types="latte">"Relaxed and content."</button>
            <button class="option-btn" data-types="iced">"Refreshed."</button>
            <button class="option-btn" data-types="mocha,cappuccino">"Satisfied and happy."</button>
          </div>
        </div>

        <div class="result" id="result">
          <h3>Your Perfect Match:</h3>
          <div class="type-badge" id="result-type">...</div>
          <p id="result-desc" style="font-size:16px; line-height:1.6; color:#555;"></p>
          
          <div class="result-actions">
            <button class="btn-action btn-retry" id="try-again">Try Again</button>
            <a href="recipes.php" class="btn-action btn-recipes" id="view-recipes">Find Recipes</a>
          </div>
        </div>

        <div class="nav-controls">
            <button class="back-btn" id="back-btn" disabled>
                <i class="fa-solid fa-arrow-left"></i> Previous Question
            </button>
        </div>

      </div>
    </div>
  </div>
</main>

<script>
    const questions = Array.from(document.querySelectorAll('.question'));
    const progressBar = document.getElementById('progress-bar');
    const resultEl = document.getElementById('result');
    const resultTypeEl = document.getElementById('result-type');
    const resultDescEl = document.getElementById('result-desc');
    const tryAgain = document.getElementById('try-again');
    const viewRecipes = document.getElementById('view-recipes');
    const backBtn = document.getElementById('back-btn');

    let index = 0;
    let scores = { espresso:0, americano:0, latte:0, cappuccino:0, mocha:0, iced:0 };
    let choiceHistory = []; 
    let weatherAnswer = null;
    let experienceAnswer = null;

    function updateProgress(){
      const pct = Math.round((index) / questions.length * 100);
      progressBar.style.width = pct + '%';
    }

    document.querySelectorAll('.option-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const types = btn.dataset.types.split(',').map(t=>t.trim()).filter(Boolean);
        choiceHistory[index] = { types: types, weather: btn.dataset.weather, exp: btn.dataset.experience };
        
        types.forEach(t => { if(scores.hasOwnProperty(t)) scores[t] += 1; });
        if(btn.dataset.weather) weatherAnswer = btn.dataset.weather;
        if(btn.dataset.experience) experienceAnswer = btn.dataset.experience;

        questions[index].classList.remove('active');
        index++;
        
        if(index < questions.length){
          questions[index].classList.add('active');
          updateProgress();
          backBtn.disabled = false;
        } else {
          progressBar.style.width = '100%';
          backBtn.style.display = 'none';
          showResult();
        }
      });
    });

    backBtn.addEventListener('click', () => {
        if(index === 0) return;
        const lastChoice = choiceHistory[index-1];
        if(lastChoice){
            lastChoice.types.forEach(t => { if(scores.hasOwnProperty(t)) scores[t] -= 1; });
        }
        questions[index].classList.remove('active');
        index--;
        questions[index].classList.add('active');
        updateProgress();
        if(index === 0) backBtn.disabled = true;
    });

    function showResult(){
      const entries = Object.entries(scores);
      entries.sort((a,b)=>b[1]-a[1]);
      const topScore = entries[0][1];
      const tied = entries.filter(e=>e[1]===topScore).map(e=>e[0]);

      let chosen = tied[0];
      
      if(tied.length > 1){
          if(weatherAnswer === 'iced' && tied.includes('iced')) chosen = 'iced';
          else if(experienceAnswer === 'treat' && tied.includes('mocha')) chosen = 'mocha';
          else if(experienceAnswer === 'cozy' && tied.includes('latte')) chosen = 'latte';
      }

      const descriptions = {
        espresso: 'Espresso — Bold, concentrated, and pure energy.',
        americano: 'Americano — Clean, strong, and straightforward.',
        latte: 'Latte — Smooth, milky, and comforting.',
        cappuccino: 'Cappuccino — The perfect balance of foam and coffee.',
        mocha: 'Mocha — A sweet chocolatey treat.',
        iced: 'Iced Coffee — Cool, refreshing, and crisp.'
      };

      resultTypeEl.textContent = chosen.charAt(0).toUpperCase() + chosen.slice(1);
      resultDescEl.textContent = descriptions[chosen];
      viewRecipes.href = 'recipes.php?search=' + chosen;

      resultEl.classList.add('active');
      questions.forEach(q=>q.classList.remove('active'));
    }

    tryAgain.addEventListener('click', () => {
      location.reload();
    });
</script>

<?php include 'footer.php'; ?>