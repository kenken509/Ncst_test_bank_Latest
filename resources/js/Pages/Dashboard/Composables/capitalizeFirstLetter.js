
export function capitalizeFirstLetter(word) {
    let tempWord = word.split('');
    let capitalLetter = tempWord[0].toUpperCase();
    let newWord = '';
    
    tempWord.forEach((char, index) => {
        if (index === 0) {
            newWord = newWord + capitalLetter;
        } else {
            newWord = newWord + char.toLowerCase();
        }
    });
    
    return newWord;
}