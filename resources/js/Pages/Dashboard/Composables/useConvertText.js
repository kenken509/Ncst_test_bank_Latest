import { ref } from 'vue';

export function useConvertText() {
    const convertedText = ref('');

    const convertText = (input) => {
        // Find the position of the first dot
        const dotIndex = input.indexOf('.');
        
        // Extract the part of the string before the first dot
        const message = input.substring(0, dotIndex + 1);
        
        // Replace the message with the desired new message
        const newMessage = message.replace('Successfully created new question.', 'Successfully created new question. vue js script setup tag');
        
        // Set the converted text
        convertedText.value = message;

    };
    
    return {
        convertedText,
        convertText
    };
}