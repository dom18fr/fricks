import React from 'react'

const Contact = () => (
  <section className="slice contact">
    <h2 className="title-2">Contact</h2>
    <header>
      <div className="contactName">Eric Frascone</div>
      <div className="contactDescription">jksldfjlksdf</div>
      <img className="contactPicture" />
    </header>
    <div className="contactPoints">
      <div className="contactPointRegular">
        <ul>
          <li className="contactPointItem email">jkdf@df.dre</li>
          <li className="contactPointItem phone">0600000000</li>
          <li className="contactPointItem address">hjkldksf sdjfildkf sdfkl</li>
        </ul>
      </div>
      <div className="contactPointSocialMedia">
        <h3 className="contactPointTitle">Résaux sociaux</h3>
        <ul>
          <li className="contactPointItem facebook">jkdf@df.dre</li>
          <li className="contactPointItem instagram">0600000000</li>
          <li className="contactPointItem youtube">hjkldksf sdjfildkf sdfkl</li>
        </ul>
      </div>
    </div>
  </section>
)

export default Contact