import React from 'react'

const YourProject = () => (
  <section className="slice yourProject">
    <h2 className="title-2">Votre projet</h2>
    <div className="yourProjectIntro">
      <ol className="yourProjectTimeline">
        <li className="yourProjectTimelineItem contact">Prise de contact</li>
        <li className="yourProjectTimelineItem plan">Plan</li>
        <li className="yourProjectTimelineItem cotation">Devis</li>
        <li className="yourProjectTimelineItem build">Réalisation</li>
      </ol>
      <p>
        Donec sollicitudin molestie malesuada. Donec rutrum congue leo eget malesuada. 
        Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.
      </p>
    </div>
    <form>
      <label>
        Nom
        <input />
      </label>
      <label>
        Prénom
        <input />
      </label>
      <label>
        Email
        <input type="email" />
      </label>
      <label>
        Votre projet en quelque mots
        <textarea/>
      </label>
      <button>
        Envoyer
      </button>
    </form>
  </section>
)

export default YourProject