// AForge Lego Robotics Library
// AForge.NET framework
//
// Copyright � Andrew Kirillov, 2007-2008
// andrew.kirillov@gmail.com
//

namespace AForge.Robotics.Lego
{
    using System;
    using AForge.Robotics.Lego.Internals;
    using System.Diagnostics;

    /// <summary>
    /// Manipulation of Lego Mindstorms NXT device.
    /// </summary>
    /// 
    /// <remarks>
    /// <para>The class allows to manipulate with Lego Mindstorms NXT device,
    /// setting/getting its motors' state, getting information about sensors'
    /// values and retrieving generic information about the NXT brick.</para>
    /// <para><img src="img/robotics/nxt.jpg" width="250" height="201" /></para>
    /// 
    /// <para><note>Only communication through Bluetooth (virtual serial port) is supported at this point.</note></para>
    /// 
    /// <para>Sample usage:</para>
    /// <code>
    /// // create an instance of NXT brick
    /// NXTBrick nxt = new NXTBrick( );
    /// // connect to the device
    /// if ( nxt.Connect( "COM8" ) )
    /// {
    ///     // run motor A
    ///     NXTBrick.MotorState motorState = new NXTBrick.MotorState( );
    /// 
    ///     motorState.Power      = 70;
    ///     motorState.TurnRatio  = 50;
    ///     motorState.Mode       = NXTBrick.MotorMode.On;
    ///     motorState.Regulation = NXTBrick.MotorRegulationMode.Idle;
    ///     motorState.RunState   = NXTBrick.MotorRunState.Running;
    ///     motorState.TachoLimit = 1000;
    /// 
    ///     nxt.SetMotorState( NXTBrick.Motor.A, motorState );
    /// 
    ///     // get input value from the first sensor
    ///     NXTBrick.SensorValues sensorValues;
    /// 
    ///     if ( nxt.GetSensorValue( NXTBrick.Sensor.First, out sensorValues ) )
    ///     {
    ///         // ...
    ///     }
    ///     // ...
    /// }
    /// </code>
    /// </remarks>
    /// 
    public partial class NXTBrick
    {
        #region Embedded types

        /// <summary>
        /// Enumeration of NXT brick sensor ports.
        /// </summary>
        public enum Sensor
        {
            /// <summary>
            /// First sensor.
            /// </summary>
            First,
            /// <summary>
            /// Second sensor.
            /// </summary>
            Second,
            /// <summary>
            /// Third sensor.
            /// </summary>
            Third,
            /// <summary>
            /// Fourth sensor.
            /// </summary>
            Fourth
        }

        /// <summary>
        /// Enumeration of NXT brick sensor types.
        /// </summary>
        /// 
        public enum SensorType
        {
            /// <summary>
            /// No sensor.
            /// </summary>
            NoSensor = 0x00,
            /// <summary>
            /// Switch (touch) sensor.
            /// </summary>
            Switch = 0x01,
            /// <summary>
            /// Temperature sensor.
            /// </summary>
            Temperature = 0x02,
            /// <summary>
            /// Reflection sensor.
            /// </summary>
            Reflection = 0x03,
            /// <summary>
            /// Angle sensor.
            /// </summary>
            Angle = 0x04,
            /// <summary>
            /// Light activity sensor.
            /// </summary>
            LightActive = 0x05,
            /// <summary>
            /// Light inactivity sensor.
            /// </summary>
            LightInactive = 0x06,
            /// <summary>
            /// Sound sensor (in dB).
            /// </summary>
            SoundDB = 0x07,
            /// <summary>
            /// Sound sensor (in dBA).
            /// </summary>
            SoundDBA = 0x08,
            /// <summary>
            /// Custom sensor.
            /// </summary>
            Custom = 0x09,
            /// <summary>
            /// Low speed sensor.
            /// </summary>
            Lowspeed = 0x0A,
            /// <summary>
            /// Low speed sensor (9V).
            /// </summary>
            Lowspeed9V = 0x0B
        }

        /// <summary>
        /// Enumeration of NXT brick sensor modes.
        /// </summary>
        /// 
        public enum SensorMode
        {
            /// <summary>
            /// Raw mode.
            /// </summary>
            Raw = 0x00,
            /// <summary>
            /// Boolean mode.
            /// </summary>
            Boolean = 0x20,
            /// <summary>
            /// Number of boolean transitions.
            /// </summary>
            TransitionCounter = 0x40,
            /// <summary>
            /// Periodic counter (number of boolean transitions divided by two).
            /// </summary>
            PeriodicCounter = 0x60,
            /// <summary>
            /// ??? (lack of documentation from Lego).
            /// </summary>
            PCTFullScale = 0x80,
            /// <summary>
            /// Celsius mode.
            /// </summary>
            Celsius = 0xA0,
            /// <summary>
            /// Fahrenheit mode.
            /// </summary>
            Fahrenheit = 0xC0,
            /// <summary>
            /// Angle steps mode.
            /// </summary>
            AngleSteps = 0xE0
        }

        /// <summary>
        /// Structure, which describes sensor's values received from NXT brick's sensor port.
        /// </summary>
        /// 
        public struct SensorValues
        {
            /// <summary>
            /// Specifies if data value should be treated as valid data.
            /// </summary>
            public bool IsValid;
            /// <summary>
            /// Specifies if calibration file was found and used for <see cref="Calibrated"/>
            /// field calculation.
            /// </summary>
            public bool IsCalibrated;
            /// <summary>
            /// Sensor type.
            /// </summary>
            public SensorType SensorType;
            /// <summary>
            /// Sensor mode.
            /// </summary>
            public SensorMode SensorMode;
            /// <summary>
            /// Raw A/D value (device dependent).
            /// </summary>
            public ushort Raw;
            /// <summary>
            /// Normalized A/D value (sensor type dependent), [0, 1023].
            /// </summary>
            public ushort Normalized;
            /// <summary>
            /// Scaled value (sensor mode dependent).
            /// </summary>
            public short Scaled;
            /// <summary>
            /// Value scaled according to calibration.
            /// </summary>
            /// 
            /// <remarks><note>According to Lego notes the value is currently unused.</note></remarks>
            /// 
            public short Calibrated;
        }

        /// <summary>
        /// Enumeration of NXT brick motor ports.
        /// </summary>
        /// 
        public enum Motor
        {
            /// <summary>
            /// Motor A.
            /// </summary>
            A = 0x00,
            /// <summary>
            /// Motor B.
            /// </summary>
            B = 0x01,
            /// <summary>
            /// Motor C.
            /// </summary>
            C = 0x02,
            /// <summary>
            /// All motors (A, B and C).
            /// </summary>
            All = 0xFF
        }

        /// <summary>
        /// Enumeration of supported motor modes.
        /// </summary>
        /// 
        /// <remarks>Motor mode is a bit field, so several modes can be combined.</remarks>
        /// 
        [FlagsAttribute]
        public enum MotorMode
        {
            /// <summary>
            /// Mode is not set.
            /// </summary>
            None = 0x00,
            /// <summary>
            /// Turn on the motor.
            /// </summary>
            On = 0x01,
            /// <summary>
            /// Brake.
            /// </summary>
            Brake = 0x02,
            /// <summary>
            /// Turn on regulated mode.
            /// </summary>
            Regulated = 0x04
        }

        /// <summary>
        /// Enumeration of motor regulation modes.
        /// </summary>
        /// 
        public enum MotorRegulationMode
        {
            /// <summary>
            /// No regulation will be enabled.
            /// </summary>
            Idle = 0x00,
            /// <summary>
            /// Power control will be enabled on specified motor.
            /// </summary>
            Speed = 0x01,
            /// <summary>
            /// Synchronization will be enabled.
            /// </summary>
            /// 
            /// <remarks><note>Synchronization need to be enabled on two motors.</note></remarks>
            /// 
            Sync = 0x02
        }

        /// <summary>
        /// Enumeration of motor run states.
        /// </summary>
        /// 
        public enum MotorRunState
        {
            /// <summary>
            /// Motor will be idle.
            /// </summary>
            Idle = 0x00,
            /// <summary>
            /// Motor will ramp-up.
            /// </summary>
            RampUp = 0x10,
            /// <summary>
            /// Motor will be running.
            /// </summary>
            Running = 0x20,
            /// <summary>
            /// Motor will ramp-down.
            /// </summary>
            RampDown = 0x40
        }

        /// <summary>
        /// Structure, which describes motor's state.
        /// </summary>
        /// 
        public struct MotorState
        {
            /// <summary>
            /// Power, [-100, 100].
            /// </summary>
            public int Power;
            /// <summary>
            /// Turn ratio, [-100, 100].
            /// </summary>
            public int TurnRatio;
            /// <summary>
            /// Mode (bit field).
            /// </summary>
            public MotorMode Mode;
            /// <summary>
            /// Regulation mode.
            /// </summary>
            public MotorRegulationMode Regulation;
            /// <summary>
            /// Run state.
            /// </summary>
            public MotorRunState RunState;
            /// <summary>
            /// Tacho limit (0 - run forever).
            /// </summary>
            /// 
            /// <remarks>The value determines motor's run limit.</remarks>
            public int TachoLimit;
            /// <summary>
            /// Number of counts since last reset of motor counter.
            /// </summary>
            /// 
            /// <remarks><note>The value is ignored when motor's state is set. The value is
            /// provided when motor's state is retrieved.</note></remarks>
            public int TachoCount;
            /// <summary>
            /// Current position relative to last programmed movement.
            /// </summary>
            /// 
            /// <remarks><note>The value is ignored when motor's state is set. The value is
            /// provided when motor's state is retrieved.</note></remarks>
            public int BlockTachoCount;
            /// <summary>
            /// Current position relative to last reset of motor's rotation sensor.
            /// </summary>
            /// 
            /// <remarks><note>The value is ignored when motor's state is set. The value is
            /// provided when motor's state is retrieved.</note></remarks>
            public int RotationCount;
        }

        #endregion

        // communication interfaced used for communication with NXT brick
        private INXTCommunicationInterface communicationInterface;

        /// <summary>
        /// Check if connection to NXT brick is established.
        /// </summary>
        /// 
        public bool IsConnected
        {
            get
            {
                lock ( this )
                {
                    return ( communicationInterface != null );
                }
            }
        }

        /// <summary>
        /// Initializes a new instance of the <see cref="NXTBrick"/> class.
        /// </summary>
        /// 
        public NXTBrick( )
        {
        }

        /// <summary>
        /// Destroys the instance of the <see cref="NXTBrick"/> class.
        /// </summary>
        /// 
        ~NXTBrick( )
		{
            Disconnect( );
		}

        /// <summary>
        /// Connect to NXT brick.
        /// </summary>
        /// 
        /// <param name="portName">Serial port name to use for communication, for example COM1.</param>
        /// 
        /// <returns>Returns <b>true</b> on successful connection or <b>false</b>
        /// otherwise.</returns>
        /// 
        /// <remarks>If connection to NXT brick was established before the call, existing connection will be reused.
        /// If it is required to force reconnection, then <see cref="Disconnect"/> method should be called before.
        /// </remarks>
        /// 
        public bool Connect( string portName )
        {
            lock ( this )
            {
                if ( communicationInterface != null )
                    return true;

                // create communication interface,
                communicationInterface = new SerialCommunication( portName );
                // connect and check if NXT is alive
                if ( ( communicationInterface.Connect( ) ) && ( IsAlive( ) ) )
                    return true;

                Disconnect( );
            }
            return false;
        }

        /// <summary>
        /// Disconnect from Lego NXT brick.
        /// </summary>
        /// 
        public void Disconnect( )
        {
            lock ( this )
            {
                if ( communicationInterface != null )
                {
                    communicationInterface.Disconnect( );
                    communicationInterface = null;
                }
            }
        }

        /// <summary>
        /// Check if the NXT brick is alive and responds to messages.
        /// </summary>
        /// 
        /// <returns>Returns <b>true</b> if device is alive or <b>false</b> otherwise.</returns>
        /// 
        /// <remarks>The command also keeps NXT brick alive preventing it from sleep.</remarks>
        /// 
        public bool IsAlive( )
        {
            return SendCommand( new byte[] { (byte) NXTCommandType.DirectCommand,
                (byte) NXTDirectCommand.KeepAlive }, new byte[7] );
        }
        /// <summary>
        /// Open write handeler.
        /// </summary>
        /// 
        /// <param name="name">Filename max[15.3].</param>
        /// 
        /// <returns>Returns <b>true</b> if device is alive or <b>false</b> otherwise.</returns>
        /// 
        public bool RunProgram(string name, long size)
        {
            byte[] command = new byte[name.Length + 3];
            char[] name_chat = name.ToCharArray();
            int i;
            // prepare command
            command[0] = (byte)NXTCommandType.SystemCommand;
            command[1] = (byte)NXTDirectCommand.OpenWrite;
            //loop to send filename
            for (i = 2; (i < 21) && (i - 2 < name.Length); i++)
            {
                command[i] = (byte)name_chat[i - 2];
            }
            command[i] = 0x00;
            //send size
            command[i+1] = (byte)size;
            command[i + 2] = (byte)(size << 8);
            command[i + 3] = (byte)(size << 16);
            command[i + 4] = (byte)(size << 24);
            //debug: show command
           
            // execute command
            return SendCommand(command, new byte[3]);
        }
        
        /// <summary>
        /// Runs program.
        /// </summary>
        /// 
        /// <param name="name">Filename max[15.3].</param>
        /// 
        /// <returns>Returns <b>true</b> if device is alive or <b>false</b> otherwise.</returns>
        /// 
        public bool RunProgram(string name)
        {
            byte[] command = new byte[name.Length+3];
            char[] name_chat = name.ToCharArray();
            int i;
            // prepare command
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.Program;
            //loop to send filename
            for (i = 2; (i < 21) && (i-2 < name.Length); i++)
            {
                command[i] = (byte)name_chat[i-2];
            }
            command[i] = 0x00;
            
            
            // execute command
            return SendCommand( command, new byte[3] );
        }
        /// <summary>
        /// Stop any program.
        /// </summary>
        /// 
        /// 
        /// <returns>Returns <b>true</b> if device is alive or <b>false</b> otherwise.</returns>
        /// 
        public bool StopProgram()
        {
            byte[] command = new byte[2];


            command[0] = (byte)NXTCommandType.DirectCommand;
            command[1] = (byte)NXTDirectCommand.StopProgram;


            // execute command
            return SendCommand(command, new byte[3]);
        }
        /// <summary>
        /// Play tone of specified frequency.
        /// </summary>
        /// 
        /// <param name="frequency">Tone frequency in Hz.</param>
        /// <param name="duration">Tone duration in milliseconds.</param>
        /// 
        /// <returns>Returns <b>true</b> if device is alive or <b>false</b> otherwise.</returns>
        /// 
        public bool PlayTone(short frequency, short duration)
        {
            byte[] command = new byte[6];

            // prepare command
            command[0] = (byte)NXTCommandType.DirectCommand;
            command[1] = (byte)NXTDirectCommand.PlayTone;
            command[2] = (byte)(frequency & 0xFF);
            command[3] = (byte)(frequency >> 8);
            command[4] = (byte)(duration & 0xFF);
            command[5] = (byte)(duration >> 8);

            // execute command
            return SendCommand(command, new byte[3]);
        }

        /// <summary>
        /// Get firmware version of NXT brick.
        /// </summary>
        /// 
        /// <param name="protocolVersion">Protocol version number.</param>
        /// <param name="firmwareVersion">Firmware version number.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        ///
        public bool GetVersion( out string protocolVersion, out string firmwareVersion )
        {
            byte[] reply = new byte[7];

            if ( SendCommand( new byte[] { (byte) NXTCommandType.SystemCommand,
                (byte) NXTSystemCommand.GetFirmwareVersion }, reply ) )
            {
                protocolVersion = string.Format( "{0}.{1}", reply[4], reply[3] );
                firmwareVersion = string.Format( "{0}.{1}", reply[6], reply[5] );
                return true;
            }

            protocolVersion = null;
            firmwareVersion = null;

            return false;
        }

        /// <summary>
        /// Get information about NXT device.
        /// </summary>
        /// 
        /// <param name="deviceName">Device name.</param>
        /// <param name="btAddress">Bluetooth address.</param>
        /// <param name="btSignalStrength">Bluetooth signal strength.</param>
        /// <param name="freeUserFlash">Free user Flash.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        ///
        public bool GetDeviceInformation( out string deviceName, out byte[] btAddress, out int btSignalStrength, out int freeUserFlash )
        {
            byte[] reply = new byte[33];
  
            if ( SendCommand( new byte[] { (byte) NXTCommandType.SystemCommand,
                (byte) NXTSystemCommand.GetDeviceInfo }, reply ) )
            {
                // devince name
                deviceName = System.Text.ASCIIEncoding.ASCII.GetString( reply, 3, 15 );
                // Bluetooth address
                btAddress = new byte[7];
                Array.Copy( reply, 18, btAddress, 0, 7 );
                // Bluetooth signal strength
                btSignalStrength = reply[25] | ( reply[26] << 8 ) |
                    ( reply[27] << 16 ) | ( reply[28] << 24 );
                // free user Flash
                freeUserFlash = reply[29] | ( reply[30] << 8 ) |
                    ( reply[31] << 16 ) | ( reply[32] << 24 );

                return true;
            }

            deviceName = null;
            btAddress = null;
            btSignalStrength = 0;
            freeUserFlash = 0;

            return false;
        }

        /// <summary>
        /// Get battery power of NXT brick.
        /// </summary>
        /// 
        /// <param name="power">NXT brick's battery power in millivolts.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool GetBatteryPower( out int power )
        {
            byte[] reply = new byte[5];

            if ( SendCommand( new byte[] { (byte) NXTCommandType.DirectCommand,
                (byte) NXTDirectCommand.GetBatteryLevel }, reply ) )
            {
                power = reply[3] | ( reply[4] << 8 );
                return true;
            }

            power = 0;

            return false;
        }

        /// <summary>
        /// Set name of NXT device.
        /// </summary>
        /// 
        /// <param name="deviceName">Device name to set for the brick.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool SetBrickName( string deviceName )
        {
            byte[] command = new byte[18];

            // prepare message
            command[0] = (byte) NXTCommandType.SystemCommand;
            command[1] = (byte) NXTSystemCommand.SetBrickName;
            // convert string to bytes
            System.Text.ASCIIEncoding.ASCII.GetBytes( deviceName, 0, Math.Min( deviceName.Length, 14 ), command, 2 );

            return SendCommand( command, new byte[3] );
        }

        /// <summary>
        /// Reset motor's position.
        /// </summary>
        /// 
        /// <param name="motor">Motor to reset.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool ResetMotorPosition( Motor motor )
        {
            byte[] command = new byte[4];

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.ResetMotorPosition;
            command[2] = (byte) motor;
            command[3] = 0; // reser absolute position

            return SendCommand( command, new byte[3] );
        }

        /// <summary>
        /// Set motor state.
        /// </summary>
        /// 
        /// <param name="motor">Motor to set state for.</param>
        /// <param name="state">Motor's state to set.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool SetMotorState( Motor motor, MotorState state )
        {
            byte[] command = new byte[12];

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.SetOutputState;
            command[2] = (byte) motor;
            command[3] = (byte) state.Power;
            command[4] = (byte) state.Mode;
            command[5] = (byte) state.Regulation;
            command[6] = (byte) state.TurnRatio;
            command[7] = (byte) state.RunState;
            // tacho limit
            command[8]  = (byte) ( state.TachoLimit & 0xFF );
            command[9]  = (byte) ( ( state.TachoLimit >> 8 ) & 0xFF );
            command[10] = (byte) ( ( state.TachoLimit >> 16 ) & 0xFF );
            command[11] = (byte) ( ( state.TachoLimit >> 24 ) & 0xFF );

            return SendCommand( command, new byte[3] );
        }

        /// <summary>
        /// Get motor state.
        /// </summary>
        /// 
        /// <param name="motor">Motor to get state for.</param>
        /// <param name="state">Motor's state.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool GetMotorState( Motor motor, out MotorState state )
        {
            state = new MotorState( );

            // check motor port
            if ( motor == Motor.All )
            {
                //throw new ArgumentException( "Motor state can be retrieved for one motor only" );
                Debug.WriteLine("Motor state can be retrieved for one motor only");
            }

            byte[] command = new byte[3];
            byte[] reply = new byte[25];

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.GetOutputState;
            command[2] = (byte) motor;

            if ( SendCommand( command, reply ) )
            {
                state.Power         = (sbyte) reply[4];
                state.Mode          = (MotorMode) reply[5];
                state.Regulation    = (MotorRegulationMode) reply[6];
                state.TurnRatio     = (sbyte) reply[7];
                state.RunState      = (MotorRunState) reply[8];

                // tacho limit
                state.TachoLimit = reply[9] | ( reply[10] << 8 ) |
                        ( reply[11] << 16 ) | ( reply[12] << 24 );
                // tacho count
                state.TachoCount = reply[13] | ( reply[14] << 8 ) |
                        ( reply[15] << 16 ) | ( reply[16] << 24 );
                // block tacho count
                state.BlockTachoCount = reply[17] | ( reply[18] << 8 ) |
                        ( reply[19] << 16 ) | ( reply[20] << 24 );
                // rotation count
                state.RotationCount = reply[21] | ( reply[22] << 8 ) |
                        ( reply[23] << 16 ) | ( reply[24] << 24 );

                return true;
            }

            return false;
        }

        /// <summary>
        /// Set sensor's type and mode.
        /// </summary>
        /// 
        /// <param name="sensor">Sensor to set type of.</param>
        /// <param name="type">Sensor's type.</param>
        /// <param name="mode">Sensor's mode.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool SetSensorMode( Sensor sensor, SensorType type, SensorMode mode )
        {
            byte[] command = new byte[5];

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.SetInputMode;
            command[2] = (byte) sensor;
            command[3] = (byte) type;
            command[4] = (byte) mode;

            return SendCommand( command, new byte[3] );
        }

        /// <summary>
        /// Get sensor's values.
        /// </summary>
        /// 
        /// <param name="sensor">Sensor to get values of.</param>
        /// <param name="sensorValues">etrieved sensor's values.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool GetSensorValue( Sensor sensor, out SensorValues sensorValues )
        {
            byte[] command = new byte[3];
            byte[] reply = new byte[16];

            sensorValues = new SensorValues( );

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.GetInputValues;
            command[2] = (byte) sensor;

            if ( SendCommand( command, reply ) )
            {
                sensorValues.IsValid        = ( reply[4] != 0 );
                sensorValues.IsCalibrated   = ( reply[5] != 0 );
                sensorValues.SensorType     = (SensorType) reply[6];
                sensorValues.SensorMode     = (SensorMode) reply[7];
                sensorValues.Raw            = (ushort) ( reply[8] | ( reply[9] << 8 ) );
                sensorValues.Normalized     = (ushort) ( reply[10] | ( reply[11] << 8 ) );
                sensorValues.Scaled         = (short) ( reply[12] | ( reply[13] << 8 ) );
                sensorValues.Calibrated     = (short) ( reply[14] | ( reply[15] << 8 ) );

                return true;
            }

            return false;
        }

        /// <summary>
        /// Clear sensor's scaled value. 
        /// </summary>
        /// 
        /// <param name="sensor">Sensor to clear value of.</param>
        /// 
        /// <returns>Returns <b>true</b> if command was executed successfully or <b>false</b> otherwise.</returns>
        /// 
        public bool ClearSensor( Sensor sensor )
        {
            byte[] command = new byte[3];

            // prepare message
            command[0] = (byte) NXTCommandType.DirectCommand;
            command[1] = (byte) NXTDirectCommand.ResetInputScaledValue;
            command[2] = (byte) sensor;

            return SendCommand( command, new byte[3] );
        }


        /// <summary>
        /// Send command to Lego NXT brick and read reply.
        /// </summary>
        /// 
        /// <param name="command">Command to send.</param>
        /// <param name="reply">Buffer to receive reply into.</param>
        /// 
        /// <returns>Returns <b>true</b> if the command was sent successfully and reply was
        /// received, otherwise <b>false</b>.</returns>
        /// 
        /// <exception cref="NullReferenceException">Communication can not be performed, because connection with
        /// NXT brick was not established yet.</exception>
        /// <exception cref="ArgumentException">Reply buffer size is smaller than the reply data size.</exception>
        /// <exception cref="ApplicationException">Reply does not correspond to command (second byte of reply should
        /// be equal to second byte of command).</exception>
        /// <exception cref="ApplicationException">Error occured on NXT brick side.</exception>
        /// 
        protected bool SendCommand( byte[] command, byte[] reply )
        {
            bool result = false;

            lock ( this )
            {
                // check connection
                if ( communicationInterface == null )
                {
                    Debug.WriteLine( "Not connected to NXT brick" );
                }

                // send message to NXT brick
                if ( communicationInterface.SendMessage( command, command.Length ) )
                {
                    int bytesRead;

                    // read message
                    if ( communicationInterface.ReadMessage( reply, out bytesRead ) )
                    {
                        // check that reply corresponds to command
                        if ( reply[1] != command[1] )
                            Debug.WriteLine("Reply does not correspond to command");

                        // check for errors
                        if ( reply[2] != 0 )
                        {
                            Debug.WriteLine("Error occured in NXT brick. Error code: " + reply[2].ToString());
                        }

                        result = true;
                    }
                }
            }

            return result;
        }
    }
}
