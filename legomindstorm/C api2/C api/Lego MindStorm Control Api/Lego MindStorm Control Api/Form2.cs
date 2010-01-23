using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace Lego_MindStorm_Control_Api
{
    public partial class Fconfig : Form
    {
        public Fconfig()
        {
            InitializeComponent();
        }

        private void Fconfig_Load(object sender, EventArgs e)
        {
            // Read the file as one string.
            System.IO.StreamReader myFile =
               new System.IO.StreamReader("settings.xml");
            string myString = myFile.ReadToEnd();

            myFile.Close();

            //display
            tbConfig.Text = myString;

        }

        private void btCancel_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void btOk_Click(object sender, EventArgs e)
        {
            
            // Write the string to a file.
            System.IO.StreamWriter file = new System.IO.StreamWriter("settings.xml");
            file.WriteLine(tbConfig.Text);

            file.Close();
             // Initializes the variables to pass to the MessageBox.Show method.

            string message = "You will need to restart the software.";
            string caption = "Restart";
            MessageBoxButtons buttons = MessageBoxButtons.OK;
            DialogResult result;

            // Displays the MessageBox.

            result = MessageBox.Show(this, message, caption, buttons,
                MessageBoxIcon.Warning, MessageBoxDefaultButton.Button1, 
                MessageBoxOptions.RightAlign);
            MessageBox.Show("You will need to restart the software.");

            
            this.Close();
        }
    }
}
